<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->string('job_code', 50)->nullable()->comment('Kode pekerjaan standar');
            $table->string('description');
            $table->string('category', 50)->nullable()->comment('engine, hydraulic, electrical, body, preventive');
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->decimal('actual_hours', 8, 2)->nullable();
            $table->foreignId('assigned_mechanic_id')->nullable()->constrained('mechanics')->nullOnDelete();
            $table->string('status', 30)->default('pending')->comment('pending, in_progress, completed, skipped');
            $table->text('findings')->nullable()->comment('Temuan mekanik');
            $table->text('action_taken')->nullable()->comment('Tindakan yang diambil');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['work_order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_items');
    }
};
