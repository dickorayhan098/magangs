<?php

namespace App\Enums;

enum MaintenanceType: string
{
    case Periodic = 'periodic';
    case Corrective = 'corrective';
    case Breakdown = 'breakdown';
    case Overhaul = 'overhaul';

    public function label(): string
    {
        return match ($this) {
            self::Periodic => 'Perawatan Berkala (PM)',
            self::Corrective => 'Perbaikan Korektif',
            self::Breakdown => 'Perbaikan Breakdown',
            self::Overhaul => 'Overhaul',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Periodic => 'info',
            self::Corrective => 'warning',
            self::Breakdown => 'danger',
            self::Overhaul => 'primary',
        };
    }
}
