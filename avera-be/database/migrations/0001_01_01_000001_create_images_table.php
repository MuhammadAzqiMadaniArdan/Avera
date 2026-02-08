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
        Schema::create('images', function (Blueprint $table) {
            $table->uuid('id')->primary();
             $table->enum('owner_type',['product','store_logo','store_banner','user_avatar','homepage_banner','promo_banner','category']);
            $table->uuid('owner_id')->nullable();
            $table->enum('disk',['s3','cloudinary','local'])->default('cloudinary');
            $table->string('path');
            $table->string('mime_type',50);
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->char('hash',64)->index();
            $table->enum('moderation_status',['pending','approved','warning','rejected'])->default('pending');
            $table->string('moderation_reason')->nullable();
            $table->json('moderation_result')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
