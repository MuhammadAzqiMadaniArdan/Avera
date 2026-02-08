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
        Schema::create('banners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->foreignUuid('primary_image_id')->nullable()->constrained('images')->cascadeOnDelete();
            $table->enum('owner_type',['admin','store']);
            $table->uuid('owner_id')->nullable();
            $table->enum('link_type',['store','product','category','internal'])->nullable();
            $table->uuid('link_id')->nullable();
            $table->string('link_url')->nullable();
            $table->enum('type',['homepage','category','promo','store','admin'])->default('homepage');
            $table->enum('audience',['public','store_user','admin'])->default('public');
            $table->smallInteger('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->enum('moderation_visibility', ['public','hidden'])->default('public');
            $table->dateTime('moderated_at')->nullable();
            $table->foreignUuid('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['owner_type','owner_id','type','audience','is_active','start_at','end_at','priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
