<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'part_number',
        'name',
        'brand',
        'category',
        'uom',
        'stock_quantity',
        'minimum_stock',
        'reorder_point',
        'unit_price',
        'warehouse_location',
        'is_critical',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'stock_quantity' => 'integer',
            'minimum_stock' => 'integer',
            'reorder_point' => 'integer',
            'unit_price' => 'decimal:2',
            'is_critical' => 'boolean',
        ];
    }

    /**
     * @return HasMany<PartsRequisition, $this>
     */
    public function requisitions(): HasMany
    {
        return $this->hasMany(PartsRequisition::class);
    }

    /**
     * Cek apakah stok di bawah minimum.
     */
    public function isBelowMinimumStock(): bool
    {
        return $this->stock_quantity <= $this->minimum_stock;
    }

    /**
     * Cek apakah stok rendah / di bawah batas minimum (alias).
     */
    public function isLowStock(): bool
    {
        return $this->isBelowMinimumStock();
    }

    /**
     * Cek apakah stok mencapai reorder point.
     */
    public function needsReorder(): bool
    {
        return $this->stock_quantity <= $this->reorder_point;
    }

    /**
     * Cek ketersediaan stok untuk jumlah tertentu.
     */
    public function hasAvailableStock(int $quantity): bool
    {
        return $this->stock_quantity >= $quantity;
    }
}
