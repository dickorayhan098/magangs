<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code', 50)->unique()->comment('Kode unit unik, misal: EXC-001');
            $table->string('name')->comment('Nama unit: Excavator CAT 320D');
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->unique();
            $table->year('year_manufactured')->nullable();
            $table->string('engine_serial', 100)->nullable();
            $table->string('category', 50)->comment('excavator, bulldozer, crane, dump_truck, dll');
            $table->decimal('current_hm', 10, 2)->default(0)->comment('Hours Meter terakhir');
            $table->decimal('last_pm_hm', 10, 2)->default(0)->comment('HM saat PM terakhir dilakukan');
            $table->string('location', 255)->nullable()->comment('Lokasi site unit saat ini');
            $table->string('status', 30)->default('available')->index();
            $table->string('ownership', 50)->default('owned')->comment('owned, rental, leased');
            $table->string('photo')->nullable();
            $table->json('specifications')->nullable()->comment('Spesifikasi teknis fleksibel');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('category');
            $table->index('current_hm');
            $table->index(['status', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
