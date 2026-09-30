<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qc_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('inspected_by_user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Lead Mechanic / QC Inspector');
            $table->json('checklist_items')->comment('Array item checklist QC dengan status pass/fail');
            $table->string('overall_result', 20)->default('pending')->comment('pending, passed, failed, conditional');
            $table->text('notes')->nullable();
            $table->json('photos')->nullable()->comment('Foto-foto bukti QC');
            $table->string('digital_signature')->nullable()->comment('Path tanda tangan digital QC');
            $table->dateTime('inspected_at')->nullable();
            $table->timestamps();

            $table->index(['work_order_id', 'overall_result']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_checklists');
    }
};
