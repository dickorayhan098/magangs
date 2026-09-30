<?php

namespace App\Enums;

enum WorkOrderStatus: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case InProgress = 'in_progress';
    case WaitingParts = 'waiting_parts';
    case QcTesting = 'qc_testing';
    case Completed = 'completed';
    case Released = 'released';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Scheduled => 'Dijadwalkan',
            self::InProgress => 'Sedang Dikerjakan',
            self::WaitingParts => 'Menunggu Parts',
            self::QcTesting => 'QC Testing',
            self::Completed => 'Selesai',
            self::Released => 'Diserahkan',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Scheduled => 'info',
            self::InProgress => 'warning',
            self::WaitingParts => 'danger',
            self::QcTesting => 'primary',
            self::Completed => 'success',
            self::Released => 'success',
            self::Cancelled => 'gray',
        };
    }

    /**
     * Transisi status yang diperbolehkan dari status saat ini.
     *
     * @return array<int, self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Draft => [self::Scheduled, self::Cancelled],
            self::Scheduled => [self::InProgress, self::Cancelled],
            self::InProgress => [self::WaitingParts, self::QcTesting, self::Cancelled],
            self::WaitingParts => [self::InProgress],
            self::QcTesting => [self::Completed, self::InProgress],
            self::Completed => [self::Released],
            self::Released => [],
            self::Cancelled => [],
        };
    }

    /**
     * Validasi apakah transisi ke status tujuan diperbolehkan.
     */
    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }
}
