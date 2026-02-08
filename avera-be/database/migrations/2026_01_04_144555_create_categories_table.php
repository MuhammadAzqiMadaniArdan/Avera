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
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('image_id')->nullable();
            $table->foreignUuid('parent_id')->nullable();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('allows_adult_content')->default(false);
            $table->text('description')->nullable();
            $table->enum('status',['active','inactive']);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('categories',function (Blueprint $table) {
            $table->foreign('image_id')->references('id')->on('images')->nullOnDelete();
            $table->foreign('parent_id')->references('id')->on('categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
