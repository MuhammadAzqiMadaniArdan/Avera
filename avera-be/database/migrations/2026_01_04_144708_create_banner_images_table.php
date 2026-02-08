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
        Schema::create('banner_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('banner_id')->constrained('banners')->cascadeOnDelete();
            $table->foreignUuid('image_id')->constrained('images')->cascadeOnDelete();
            $table->smallInteger('position')->default(0);
            $table->integer('replace_count')->default(0);
            $table->dateTime('last_replaced_at')->nullable();
            $table->enum('status',['temp','active','archived'])->default('temp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_images');
    }
};
