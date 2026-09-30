<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mechanic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_id',
        'name',
        'phone',
        'specialization',
        'certification_level',
        'is_active',
        'is_available',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_available' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<WorkOrder, $this>
     */
    public function assignedWorkOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'assigned_mechanic_id');
    }

    /**
     * @return HasMany<WorkOrderItem, $this>
     */
    public function assignedItems(): HasMany
    {
        return $this->hasMany(WorkOrderItem::class, 'assigned_mechanic_id');
    }

    /**
     * Hitung jumlah WO aktif yang sedang ditangani.
     */
    public function activeWorkOrdersCount(): int
    {
        return $this->assignedWorkOrders()
            ->whereIn('status', ['in_progress', 'waiting_parts', 'qc_testing'])
            ->count();
    }
}
