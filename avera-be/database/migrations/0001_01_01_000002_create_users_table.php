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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('identity_core_id')->unique();
            $table->foreignUuid('avatar_image_id')->nullable();
            $table->string('username')->unique();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->enum('role', ['user', 'seller', 'admin'])->default('user');
            $table->enum('status', ['active', 'warning', 'suspended', 'banned'])->default('active');
            $table->enum('gender', ['male','female','other'])->default('other');
            $table->string('phone_number')->nullable();
            $table->dateTime('suspended_until')->nullable();
            $table->integer('violation_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('avatar_image_id')->references('id')->on('images')->nullOnDelete();
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
