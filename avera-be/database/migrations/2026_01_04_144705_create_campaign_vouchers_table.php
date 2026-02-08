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
        Schema::create('campaign_vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('campaign_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('code')->unique();

            $table->enum('type', ['free_shipping', 'cashback', 'discount']);

            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 12, 2)->nullable();
            $table->decimal('min_order_amount', 12, 2)->nullable();
            $table->decimal('max_discount', 12, 2)->nullable();

            $table->integer('total_quota')->nullable();
            $table->integer('claimed_count')->default(0);
            $table->integer('usage_limit_per_user')->default(1);

            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_vouchers');
    }
};
