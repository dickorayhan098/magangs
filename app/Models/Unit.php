<?php

namespace App\Models;

use App\Enums\UnitStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unit_code',
        'name',
        'brand',
        'model',
        'serial_number',
        'year_manufactured',
        'engine_serial',
        'category',
        'current_hm',
        'last_pm_hm',
        'location',
        'status',
        'ownership',
        'photo',
        'specifications',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => UnitStatus::class,
            'current_hm' => 'decimal:2',
            'last_pm_hm' => 'decimal:2',
            'specifications' => 'array',
            'year_manufactured' => 'integer',
        ];
    }

    /**
     * @return HasMany<HmLog, $this>
     */
    public function hmLogs(): HasMany
    {
        return $this->hasMany(HmLog::class);
    }

    /**
     * @return HasMany<WorkOrder, $this>
     */
    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    /**
     * Cek apakah unit sudah mendekati jadwal PM berikutnya.
     */
    public function isApproachingPm(int $thresholdHours = 50): bool
    {
        $intervals = [250, 500, 1000, 2000];
        $hmSinceLastPm = $this->current_hm - $this->last_pm_hm;

        foreach ($intervals as $interval) {
            if ($hmSinceLastPm >= ($interval - $thresholdHours) && $hmSinceLastPm < $interval) {
                return true;
            }
        }

        return false;
    }

    /**
     * Hitung interval PM terdekat yang harus dilakukan.
     */
    public function getNextPmInterval(): int
    {
        $intervals = [250, 500, 1000, 2000];
        $hmSinceLastPm = (float) $this->current_hm - (float) $this->last_pm_hm;

        foreach ($intervals as $interval) {
            if ($hmSinceLastPm < $interval) {
                return $interval;
            }
        }

        return 2000;
    }

    /**
     * Hitung sisa jam operasi (HM) menuju jadwal PM berikutnya.
     */
    public function getHoursUntilNextPm(): float
    {
        $interval = $this->getNextPmInterval();
        $targetHm = (float) $this->last_pm_hm + $interval;
        $remaining = $targetHm - (float) $this->current_hm;

        return max(0.0, (float) round($remaining, 2));
    }
}
