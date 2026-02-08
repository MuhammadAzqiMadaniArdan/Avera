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
        Schema::create('stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignUuid('logo_image_id')->nullable();
            $table->string('name')->unique();
            $table->enum('status',['active','suspended'])->default('active');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('rating',3,2)->default(0);
            $table->enum('verification_status',['unverified','pending','verified','rejected','suspended'])->default('unverified');
            $table->dateTime('verified_at')->nullable();
            $table->foreignUuid('verified_by')->nullable();
            $table->decimal('rating_avg',3,2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status','verified_at']);
        });
        Schema::table('stores', function (Blueprint $table) {
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('logo_image_id')->references('id')->on('images')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
