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
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method')->nullable();
            // cod, bank_transfer, ewallet, qris
            $table->string('payment_gateway')->nullable();
            // midtrans, xendit, null for COD
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');

            $table->string('gateway_status')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->text('payment_url')->nullable();
            $table->string('signature_key')->nullable();
            $table->timestamps();
            
            $table->unique(['payment_gateway', 'transaction_id']);
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
