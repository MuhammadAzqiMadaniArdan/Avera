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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('store_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignUuid('primary_image_id')->nullable()->constrained('images')->onDelete('set null');
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('weight')->default(0);
            $table->enum('status', ['draft', 'active', 'inactive', 'archived'])->default('draft');
            $table->enum('age_rating', ['all', '13+', '18+'])->default('all');
            $table->unsignedInteger('min_purchase')->default(1);
            $table->enum('hazard_type', [
                'none',
                'battery',
                'liquid',
                'flammable',
            ])->default('none');
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('new');
            $table->enum('moderation_visibility', ['public', 'limited', 'adult', 'hidden'])->default('hidden');
            $table->json('specifications')->nullable();
            $table->bigInteger('views_count')->default(0);
            $table->bigInteger('sales_count')->default(0);
            $table->bigInteger('rating_avg')->default(0);
            $table->bigInteger('rating_count')->default(0);
            $table->dateTime('moderated_at')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['store_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
