<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parts_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_number', 50)->unique()->comment('Format: PR-YYYYMMDD-XXXX');
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->foreignId('spare_part_id')->constrained('spare_parts')->restrictOnDelete();
            $table->integer('quantity_requested');
            $table->integer('quantity_issued')->default(0);
            $table->string('status', 30)->default('pending')->index();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('issued_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('issued_at')->nullable();
            $table->decimal('unit_price', 15, 2)->default(0)->comment('Harga saat pengeluaran');
            $table->decimal('total_price', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['work_order_id', 'status']);
            $table->index('spare_part_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parts_requisitions');
    }
};
