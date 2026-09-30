<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_number', 50)->unique()->comment('Format: WO-YYYYMMDD-XXXX');
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->string('maintenance_type', 30)->comment('periodic, corrective, breakdown, overhaul');
            $table->string('priority', 20)->default('medium');
            $table->string('status', 30)->default('draft')->index();

            // Service Advisor Intake
            $table->foreignId('reported_by_user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Service Advisor');
            $table->decimal('intake_hm', 10, 2)->nullable()->comment('HM saat unit masuk workshop');
            $table->text('fault_description')->nullable()->comment('Deskripsi keluhan/kerusakan');
            $table->json('intake_checklist')->nullable()->comment('Digital checklist saat intake');
            $table->json('photos')->nullable()->comment('Foto-foto saat intake');

            // PM Schedule Reference
            $table->integer('pm_interval')->nullable()->comment('Interval PM: 250, 500, 1000, 2000 jam');
            $table->decimal('next_pm_hm', 10, 2)->nullable()->comment('HM target PM berikutnya');

            // Workshop Assignment
            $table->foreignId('assigned_mechanic_id')->nullable()->constrained('mechanics')->nullOnDelete();
            $table->foreignId('foreman_id')->nullable()->constrained('users')->nullOnDelete()->comment('Job Controller/Foreman');
            $table->dateTime('scheduled_start')->nullable();
            $table->dateTime('scheduled_end')->nullable();
            $table->dateTime('actual_start')->nullable();
            $table->dateTime('actual_end')->nullable();
            $table->decimal('estimated_hours', 8, 2)->nullable()->comment('Estimasi jam kerja');
            $table->decimal('actual_hours', 8, 2)->nullable()->comment('Aktual jam kerja');

            // QC & Completion
            $table->foreignId('qc_approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('qc_approved_at')->nullable();
            $table->json('qc_results')->nullable()->comment('Hasil QC checklist');
            $table->text('qc_notes')->nullable();

            // Handover / Release
            $table->foreignId('released_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('released_at')->nullable();
            $table->string('release_signature')->nullable()->comment('Path tanda tangan digital');

            // Cost Summary
            $table->decimal('total_parts_cost', 15, 2)->default(0);
            $table->decimal('total_labor_cost', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('maintenance_type');
            $table->index('priority');
            $table->index(['unit_id', 'status']);
            $table->index(['status', 'maintenance_type']);
            $table->index('scheduled_start');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
