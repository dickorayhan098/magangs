<?php

namespace App\Enums;

enum RequisitionStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Issued = 'issued';
    case Rejected = 'rejected';
    case BackOrder = 'back_order';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Persetujuan',
            self::Approved => 'Disetujui',
            self::Issued => 'Sudah Dikeluarkan',
            self::Rejected => 'Ditolak',
            self::BackOrder => 'Back Order',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'info',
            self::Issued => 'success',
            self::Rejected => 'danger',
            self::BackOrder => 'gray',
        };
    }
}
