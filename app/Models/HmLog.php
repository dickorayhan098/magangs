<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HmLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'hm_value',
        'previous_hm',
        'delta_hm',
        'recorded_date',
        'recorded_by',
        'recorded_by_user_id',
        'source',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hm_value' => 'decimal:2',
            'previous_hm' => 'decimal:2',
            'delta_hm' => 'decimal:2',
            'recorded_date' => 'date',
        ];
    }

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
    public function recordedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }
}
