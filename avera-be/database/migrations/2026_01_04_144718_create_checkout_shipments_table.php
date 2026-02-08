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
        Schema::create('checkout_shipments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('checkout_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('store_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_address_id')->constrained()->cascadeOnDelete();
            $table->string('courier_code');
            $table->string('courier_name');
            $table->string('service');
            $table->string('description');
            $table->string('etd')->nullable();
            $table->unsignedTinyInteger('min_days')->nullable();
            $table->unsignedTinyInteger('max_days')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->boolean('is_selected')->default(false);
            $table->timestamps();

            $table->index(['checkout_id', 'store_id']);
            $table->index(['checkout_id', 'is_selected']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_shipments');
    }
};
