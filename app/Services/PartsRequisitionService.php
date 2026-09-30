<?php

namespace App\Services;

use App\Enums\RequisitionStatus;
use App\Models\PartsRequisition;
use App\Models\SparePart;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PartsRequisitionService
{
    /**
     * Generate nomor requisition unik: PR-YYYYMMDD-XXXX.
     */
    public function generateRequisitionNumber(): string
    {
        $today = now()->format('Ymd');
        $prefix = "PR-{$today}-";

        $last = PartsRequisition::where('requisition_number', 'like', "{$prefix}%")
            ->orderByDesc('requisition_number')
            ->value('requisition_number');

        $nextSequence = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix.str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Buat permintaan spare part dari mekanik.
     *
     * @param array{
     *     work_order_id: int,
     *     spare_part_id: int,
     *     quantity_requested: int,
     *     notes?: string,
     * } $data
     */
    public function createRequisition(array $data): PartsRequisition
    {
        $workOrder = WorkOrder::findOrFail($data['work_order_id']);

        if (! $workOrder->isActive()) {
            throw new InvalidArgumentException('Parts requisition hanya bisa dibuat untuk WO yang sedang aktif.');
        }

        $sparePart = SparePart::findOrFail($data['spare_part_id']);

        return PartsRequisition::create([
            'requisition_number' => $this->generateRequisitionNumber(),
            'work_order_id' => $workOrder->id,
            'spare_part_id' => $sparePart->id,
            'quantity_requested' => $data['quantity_requested'],
            'status' => RequisitionStatus::Pending,
            'requested_by_user_id' => Auth::id(),
            'unit_price' => $sparePart->unit_price,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Approve requisition oleh Inventory Clerk / Foreman.
     */
    public function approveRequisition(PartsRequisition $requisition): PartsRequisition
    {
        if ($requisition->status !== RequisitionStatus::Pending) {
            throw new InvalidArgumentException('Hanya requisition berstatus Pending yang bisa di-approve.');
        }

        $requisition->update([
            'status' => RequisitionStatus::Approved,
            'approved_by_user_id' => Auth::id(),
            'approved_at' => now(),
        ]);

        return $requisition->refresh();
    }

    /**
     * Issue (keluarkan) spare part dari gudang — potong stok.
     */
    public function issueRequisition(PartsRequisition $requisition, ?int $quantityIssued = null): PartsRequisition
    {
        if ($requisition->status !== RequisitionStatus::Approved) {
            throw new InvalidArgumentException('Hanya requisition yang sudah di-approve yang bisa di-issue.');
        }

        $quantityToIssue = $quantityIssued ?? $requisition->quantity_requested;

        return DB::transaction(function () use ($requisition, $quantityToIssue): PartsRequisition {
            $sparePart = $requisition->sparePart;

            // Validasi ketersediaan stok
            if (! $sparePart->hasAvailableStock($quantityToIssue)) {
                // Set status ke Back Order jika stok tidak cukup
                $requisition->update([
                    'status' => RequisitionStatus::BackOrder,
                ]);

                throw new InvalidArgumentException(
                    "Stok tidak mencukupi. Tersedia: {$sparePart->stock_quantity}, diminta: {$quantityToIssue}. Status diubah ke Back Order."
                );
            }

            // Potong stok
            $sparePart->decrement('stock_quantity', $quantityToIssue);

            // Update requisition
            $totalPrice = $requisition->unit_price * $quantityToIssue;
            $requisition->update([
                'quantity_issued' => $quantityToIssue,
                'total_price' => $totalPrice,
                'status' => RequisitionStatus::Issued,
                'issued_by_user_id' => Auth::id(),
                'issued_at' => now(),
            ]);

            // Recalculate WO cost
            $requisition->workOrder->recalculateCosts();

            return $requisition->refresh();
        });
    }

    /**
     * Reject requisition.
     */
    public function rejectRequisition(PartsRequisition $requisition, string $reason): PartsRequisition
    {
        if ($requisition->status !== RequisitionStatus::Pending) {
            throw new InvalidArgumentException('Hanya requisition berstatus Pending yang bisa di-reject.');
        }

        $requisition->update([
            'status' => RequisitionStatus::Rejected,
            'approved_by_user_id' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);

        return $requisition->refresh();
    }
}
