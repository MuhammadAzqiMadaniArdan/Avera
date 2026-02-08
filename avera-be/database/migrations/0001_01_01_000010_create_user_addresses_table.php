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
        Schema::create('user_addresses', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();

    $table->string('label');
    $table->string('recipient_name');
    $table->string('recipient_phone', 16);

    $table->foreignId('province_id')->constrained()->restrictOnDelete();
    $table->foreignId('city_id')->constrained()->restrictOnDelete();
    $table->foreignId('district_id')->constrained ()->restrictOnDelete();
    $table->unsignedInteger('village_id');

    $table->string('province_name');
    $table->string('city_name');
    $table->string('district_name');
    $table->string('village_name');

    $table->string('postal_code')->nullable();
    $table->text('address');
    $table->string('other')->nullable();

    $table->boolean('is_default')->default(false);
    $table->timestamps();

    $table->index(['user_id']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
