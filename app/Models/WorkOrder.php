<?php

namespace App\Models;

use App\Enums\MaintenanceType;
use App\Enums\Priority;
use App\Enums\WorkOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wo_number',
        'unit_id',
        'maintenance_type',
        'priority',
        'status',
        'reported_by_user_id',
        'intake_hm',
        'fault_description',
        'intake_checklist',
        'photos',
        'pm_interval',
        'next_pm_hm',
        'assigned_mechanic_id',
        'foreman_id',
        'scheduled_start',
        'scheduled_end',
        'actual_start',
        'actual_end',
        'estimated_hours',
        'actual_hours',
        'qc_approved_by_user_id',
        'qc_approved_at',
        'qc_results',
        'qc_notes',
        'released_by_user_id',
        'released_at',
        'release_signature',
        'total_parts_cost',
        'total_labor_cost',
        'total_cost',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => WorkOrderStatus::class,
            'maintenance_type' => MaintenanceType::class,
            'priority' => Priority::class,
            'intake_hm' => 'decimal:2',
            'next_pm_hm' => 'decimal:2',
            'intake_checklist' => 'array',
            'photos' => 'array',
            'qc_results' => 'array',
            'scheduled_start' => 'datetime',
            'scheduled_end' => 'datetime',
            'actual_start' => 'datetime',
            'actual_end' => 'datetime',
            'qc_approved_at' => 'datetime',
            'released_at' => 'datetime',
            'estimated_hours' => 'decimal:2',
            'actual_hours' => 'decimal:2',
            'total_parts_cost' => 'decimal:2',
            'total_labor_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    // ─── Relationships ─────────────────────────────────────────

    /**
     * @return BelongsTo<Unit, $this>
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }

    /**
     * @return BelongsTo<Mechanic, $this>
     */
    public function assignedMechanic(): BelongsTo
    {
        return $this->belongsTo(Mechanic::class, 'assigned_mechanic_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function foreman(): BelongsTo
    {
        return $this->belongsTo(User::class, 'foreman_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function qcApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qc_approved_by_user_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function releasedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'released_by_user_id');
    }

    /**
     * @return HasMany<WorkOrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(WorkOrderItem::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<PartsRequisition, $this>
     */
    public function partsRequisitions(): HasMany
    {
        return $this->hasMany(PartsRequisition::class);
    }

    /**
     * @return HasOne<QcChecklist, $this>
     */
    public function qcChecklist(): HasOne
    {
        return $this->hasOne(QcChecklist::class);
    }

    /**
     * @return HasMany<WorkOrderStatusLog, $this>
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(WorkOrderStatusLog::class)->orderByDesc('created_at');
    }

    // ─── Business Logic ────────────────────────────────────────

    /**
     * Cek apakah transisi ke status tertentu diperbolehkan.
     */
    public function canTransitionTo(WorkOrderStatus $targetStatus): bool
    {
        return $this->status->canTransitionTo($targetStatus);
    }

    /**
     * Apakah WO ini bisa di-edit (belum dikerjakan).
     */
    public function isEditable(): bool
    {
        return in_array($this->status, [
            WorkOrderStatus::Draft,
            WorkOrderStatus::Scheduled,
        ], true);
    }

    /**
     * Apakah WO sedang aktif dikerjakan.
     */
    public function isActive(): bool
    {
        return in_array($this->status, [
            WorkOrderStatus::InProgress,
            WorkOrderStatus::WaitingParts,
            WorkOrderStatus::QcTesting,
        ], true);
    }

    /**
     * Hitung ulang total biaya dari parts requisitions.
     */
    public function recalculateCosts(): void
    {
        $totalPartsCost = $this->partsRequisitions()
            ->where('status', 'issued')
            ->sum('total_price');

        $this->update([
            'total_parts_cost' => $totalPartsCost,
            'total_cost' => $totalPartsCost + $this->total_labor_cost,
        ]);
    }
}
