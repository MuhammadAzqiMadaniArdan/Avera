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
        Schema::create('shipments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('store_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_address_id')->nullable()->constrained()->nullOnDelete();
            $table->string('courier_code');
            $table->string('courier_name');
            $table->string('service');
            $table->string('description');
            $table->unsignedTinyInteger('min_days')->nullable();
            $table->unsignedTinyInteger('max_days')->nullable();
            $table->string('tracking_number', 100)->unique()->nullable();
            $table->enum('status', ['pending', 'picked_up', 'in_transit', 'delivered', 'failed', 'returned'])->default('pending');
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->text('recipient_address')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
