<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_gateway')->default('midtrans');
            $table->string('transaction_id')->unique();
            $table->string('payment_type')->nullable();
            $table->decimal('gross_amount',12,2);
            $table->enum('status',['pending','capture','settlement','cancel','expire','deny','refund'])->default('pending');
            $table->text('payment_url')->nullable();
            $table->string('signature_key')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
