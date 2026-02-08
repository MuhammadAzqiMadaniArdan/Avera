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
        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->constrained()->cascadeOnDelete();
            $table->uuidMorphs('voucherable');

            $table->enum('status', [
                'unused',
                'used',
                'expired'
            ])->default('unused');

            $table->timestamp('claimed_at');
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'voucherable_id','voucherable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_vouchers');
    }
};
