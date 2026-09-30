<?php

namespace App\Enums;

enum UnitStatus: string
{
    case Available = 'available';
    case InService = 'in_service';
    case Breakdown = 'breakdown';
    case Standby = 'standby';
    case Decommissioned = 'decommissioned';

    /**
     * Label untuk ditampilkan di UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Available => 'Tersedia',
            self::InService => 'Dalam Servis',
            self::Breakdown => 'Breakdown',
            self::Standby => 'Standby',
            self::Decommissioned => 'Dinonaktifkan',
        };
    }

    /**
     * Warna badge untuk Filament.
     */
    public function color(): string
    {
        return match ($this) {
            self::Available => 'success',
            self::InService => 'warning',
            self::Breakdown => 'danger',
            self::Standby => 'gray',
            self::Decommissioned => 'gray',
        };
    }
}
