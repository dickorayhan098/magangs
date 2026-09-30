<?php

namespace App\Services;

use App\Enums\MaintenanceType;
use App\Enums\Priority;
use App\Enums\UnitStatus;
use App\Enums\WorkOrderStatus;
use App\Models\HmLog;
use App\Models\Unit;
use App\Models\WorkOrder;
use App\Models\WorkOrderStatusLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WorkOrderService
{
    // ─── WO Number Generation ──────────────────────────────────

    /**
     * Generate nomor WO unik dengan format WO-YYYYMMDD-XXXX.
     */
    public function generateWoNumber(): string
    {
        $today = now()->format('Ymd');
        $prefix = "WO-{$today}-";

        $lastWo = WorkOrder::where('wo_number', 'like', "{$prefix}%")
            ->orderByDesc('wo_number')
            ->value('wo_number');

        if ($lastWo) {
            $lastSequence = (int) substr($lastWo, -4);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix.str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    // ─── Service Advisor Intake ────────────────────────────────

    /**
     * Proses intake unit ke workshop (Service Advisor Style).
     * Mencatat HM, membuat WO baru, dan update status unit.
     *
     * @param array{
     *     unit_id: int,
     *     intake_hm: float,
     *     maintenance_type: string,
     *     priority?: string,
     *     fault_description?: string,
     *     intake_checklist?: array<int, array<string, mixed>>,
     *     photos?: array<int, string>,
     *     pm_interval?: int,
     *     notes?: string,
     * } $data
     */
    public function createIntake(array $data): WorkOrder
    {
        return DB::transaction(function () use ($data): WorkOrder {
            $unit = Unit::findOrFail($data['unit_id']);

            // Validasi HM: tidak boleh lebih kecil dari HM terakhir
            $this->validateHoursMeterId($unit, (float) $data['intake_hm']);

            // Catat HM Log
            $this->recordHmLog($unit, (float) $data['intake_hm']);

            // Tentukan PM interval jika tipe maintenance periodic
            $maintenanceType = MaintenanceType::from($data['maintenance_type']);
            $pmInterval = null;
            $nextPmHm = null;

            if ($maintenanceType === MaintenanceType::Periodic) {
                $pmInterval = $data['pm_interval'] ?? $unit->getNextPmInterval();
                $nextPmHm = $unit->last_pm_hm + $pmInterval;
            }

            // Buat Work Order
            $workOrder = WorkOrder::create([
                'wo_number' => $this->generateWoNumber(),
                'unit_id' => $unit->id,
                'maintenance_type' => $maintenanceType,
                'priority' => Priority::from($data['priority'] ?? 'medium'),
                'status' => WorkOrderStatus::Draft,
                'reported_by_user_id' => Auth::id(),
                'intake_hm' => $data['intake_hm'],
                'fault_description' => $data['fault_description'] ?? null,
                'intake_checklist' => $data['intake_checklist'] ?? null,
                'photos' => $data['photos'] ?? null,
                'pm_interval' => $pmInterval,
                'next_pm_hm' => $nextPmHm,
                'notes' => $data['notes'] ?? null,
            ]);

            // Update status unit menjadi In Service
            $unit->update(['status' => UnitStatus::InService]);

            // Log status awal
            $this->logStatusTransition($workOrder, null, WorkOrderStatus::Draft);

            return $workOrder;
        });
    }

    // ─── Status Transitions ────────────────────────────────────

    /**
     * Transisi status Work Order dengan validasi state machine.
     */
    public function transitionStatus(
        WorkOrder $workOrder,
        WorkOrderStatus $targetStatus,
        ?string $remarks = null,
    ): WorkOrder {
        if (! $workOrder->canTransitionTo($targetStatus)) {
            throw new InvalidArgumentException(
                "Transisi dari '{$workOrder->status->label()}' ke '{$targetStatus->label()}' tidak diperbolehkan."
            );
        }

        return DB::transaction(function () use ($workOrder, $targetStatus, $remarks): WorkOrder {
            $fromStatus = $workOrder->status;

            // Aksi khusus berdasarkan target status
            $this->executeStatusAction($workOrder, $targetStatus);

            $workOrder->update(['status' => $targetStatus]);

            $this->logStatusTransition($workOrder, $fromStatus, $targetStatus, $remarks);

            return $workOrder->refresh();
        });
    }

    /**
     * Mulai pengerjaan WO (Scheduled -> In Progress).
     */
    public function startWork(WorkOrder $workOrder): WorkOrder
    {
        $workOrder->update(['actual_start' => now()]);

        return $this->transitionStatus(
            $workOrder,
            WorkOrderStatus::InProgress,
            'Pengerjaan dimulai oleh mekanik.'
        );
    }

    /**
     * Tandai WO menunggu parts (In Progress -> Waiting Parts).
     */
    public function markWaitingParts(WorkOrder $workOrder, string $reason): WorkOrder
    {
        return $this->transitionStatus(
            $workOrder,
            WorkOrderStatus::WaitingParts,
            "Menunggu parts: {$reason}"
        );
    }

    /**
     * Submit WO untuk QC (In Progress -> QC Testing).
     */
    public function submitForQc(WorkOrder $workOrder): WorkOrder
    {
        $workOrder->update(['actual_end' => now()]);

        // Hitung actual hours
        if ($workOrder->actual_start) {
            $actualHours = $workOrder->actual_start->diffInMinutes(now()) / 60;
            $workOrder->update(['actual_hours' => round($actualHours, 2)]);
        }

        return $this->transitionStatus(
            $workOrder,
            WorkOrderStatus::QcTesting,
            'Pengerjaan selesai, diserahkan ke QC.'
        );
    }

    /**
     * Approve QC dan tandai WO completed.
     */
    public function approveQc(WorkOrder $workOrder, ?string $qcNotes = null): WorkOrder
    {
        $workOrder->update([
            'qc_approved_by_user_id' => Auth::id(),
            'qc_approved_at' => now(),
            'qc_notes' => $qcNotes,
        ]);

        return $this->transitionStatus(
            $workOrder,
            WorkOrderStatus::Completed,
            'QC approved: unit lulus inspeksi.'
        );
    }

    /**
     * QC reject, kembalikan ke In Progress.
     */
    public function rejectQc(WorkOrder $workOrder, string $reason): WorkOrder
    {
        $workOrder->update([
            'actual_end' => null,
            'qc_notes' => $reason,
        ]);

        return $this->transitionStatus(
            $workOrder,
            WorkOrderStatus::InProgress,
            "QC rejected: {$reason}"
        );
    }

    /**
     * Release unit (Completed -> Released) — Handover ke customer/operator.
     */
    public function releaseUnit(WorkOrder $workOrder): WorkOrder
    {
        return DB::transaction(function () use ($workOrder): WorkOrder {
            $workOrder->update([
                'released_by_user_id' => Auth::id(),
                'released_at' => now(),
            ]);

            // Hitung total cost terakhir
            $workOrder->recalculateCosts();

            // Update status unit kembali Available & update PM HM jika periodic
            $unit = $workOrder->unit;
            $unit->update(['status' => UnitStatus::Available]);

            if ($workOrder->maintenance_type === MaintenanceType::Periodic) {
                $unit->update(['last_pm_hm' => $workOrder->intake_hm]);
            }

            return $this->transitionStatus(
                $workOrder,
                WorkOrderStatus::Released,
                'Unit diserahkan kembali ke operator.'
            );
        });
    }

    // ─── HM Validation & Recording ────────────────────────────

    /**
     * Validasi HM baru harus >= HM terakhir unit.
     */
    private function validateHoursMeterId(Unit $unit, float $newHm): void
    {
        if ($newHm < $unit->current_hm) {
            throw new InvalidArgumentException(
                "Hours Meter baru ({$newHm}) tidak boleh lebih kecil dari HM terakhir ({$unit->current_hm})."
            );
        }
    }

    /**
     * Catat HM Log dan update current_hm pada unit.
     */
    private function recordHmLog(Unit $unit, float $newHm): HmLog
    {
        $previousHm = $unit->current_hm;
        $deltaHm = $newHm - $previousHm;

        $hmLog = HmLog::create([
            'unit_id' => $unit->id,
            'hm_value' => $newHm,
            'previous_hm' => $previousHm,
            'delta_hm' => $deltaHm,
            'recorded_date' => now()->toDateString(),
            'recorded_by_user_id' => Auth::id(),
            'source' => 'manual',
        ]);

        $unit->update(['current_hm' => $newHm]);

        return $hmLog;
    }

    // ─── Status Action Handler ─────────────────────────────────

    /**
     * Eksekusi aksi spesifik saat status berubah.
     */
    private function executeStatusAction(WorkOrder $workOrder, WorkOrderStatus $targetStatus): void
    {
        match ($targetStatus) {
            WorkOrderStatus::InProgress => $workOrder->unit->update(['status' => UnitStatus::InService]),
            WorkOrderStatus::Released => $workOrder->unit->update(['status' => UnitStatus::Available]),
            WorkOrderStatus::Cancelled => $workOrder->unit->update(['status' => UnitStatus::Available]),
            default => null,
        };
    }

    // ─── Audit Log ─────────────────────────────────────────────

    /**
     * Catat transisi status ke log.
     */
    private function logStatusTransition(
        WorkOrder $workOrder,
        ?WorkOrderStatus $from,
        WorkOrderStatus $to,
        ?string $remarks = null,
    ): void {
        WorkOrderStatusLog::create([
            'work_order_id' => $workOrder->id,
            'from_status' => $from?->value,
            'to_status' => $to->value,
            'changed_by_user_id' => Auth::id(),
            'remarks' => $remarks,
        ]);
    }
}
