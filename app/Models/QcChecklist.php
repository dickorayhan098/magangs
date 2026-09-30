<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QcChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'inspected_by_user_id',
        'checklist_items',
        'overall_result',
        'notes',
        'photos',
        'digital_signature',
        'inspected_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'checklist_items' => 'array',
            'photos' => 'array',
            'inspected_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<WorkOrder, $this>
     */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function inspectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspected_by_user_id');
    }

    /**
     * Cek apakah semua item checklist passed.
     */
    public function isAllPassed(): bool
    {
        if (! is_array($this->checklist_items)) {
            return false;
        }

        return collect($this->checklist_items)
            ->every(fn (array $item): bool => ($item['result'] ?? null) === 'pass');
    }
}
