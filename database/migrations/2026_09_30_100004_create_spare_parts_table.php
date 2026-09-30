<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('part_number', 100)->unique()->comment('Nomor part OEM/aftermarket');
            $table->string('name');
            $table->string('brand', 100)->nullable();
            $table->string('category', 100)->nullable()->comment('filter, seal, belt, bearing, dll');
            $table->string('uom', 20)->default('pcs')->comment('Unit of Measure: pcs, liter, kg, set');
            $table->integer('stock_quantity')->default(0);
            $table->integer('minimum_stock')->default(0)->comment('Safety stock level');
            $table->integer('reorder_point')->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->string('warehouse_location', 100)->nullable()->comment('Lokasi rak gudang');
            $table->boolean('is_critical')->default(false)->comment('Part kritikal untuk operasional');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('category');
            $table->index('stock_quantity');
            $table->index('is_critical');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
