<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mechanics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('employee_id', 50)->unique()->comment('NIK / ID karyawan');
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('specialization', 100)->nullable()->comment('engine, hydraulic, electrical, dll');
            $table->string('certification_level', 50)->default('junior')->comment('junior, senior, lead, specialist');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true)->comment('Tersedia untuk assignment');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('is_available');
            $table->index('specialization');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mechanics');
    }
};
