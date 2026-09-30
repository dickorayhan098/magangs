<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hm_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->decimal('hm_value', 10, 2)->comment('Pembacaan Hours Meter');
            $table->decimal('previous_hm', 10, 2)->nullable()->comment('HM sebelumnya untuk validasi');
            $table->decimal('delta_hm', 10, 2)->nullable()->comment('Selisih dengan HM sebelumnya');
            $table->date('recorded_date');
            $table->string('recorded_by')->nullable()->comment('Nama operator/pencatat');
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source', 30)->default('manual')->comment('manual, gps_tracker, iot_sensor');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['unit_id', 'recorded_date']);
            $table->index(['unit_id', 'hm_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hm_logs');
    }
};
