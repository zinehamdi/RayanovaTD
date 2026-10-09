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
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->json('title');          // {"ar": "...", "en": "...", "fr": "..."}
            $table->json('subtitle')->nullable();
            $table->json('description')->nullable();
            $table->string('featured_image');
            $table->json('gallery')->nullable();
            
            // Attributs techniques dynamiques (EAV JSON)
            $table->json('nutritional_specs')->nullable(); // acidity, polyphenol, peroxides, variety, harvest_year
            $table->json('logistics_specs')->nullable();   // gross_weight, units_per_box, boxes_per_pallet
            
            // Configurations de conditionnement autorisées pour le Wheel Picker
            $table->json('allowed_packaging_types');        // ["bulk", "bottle_glass_marasca", "tin_can", etc.]
            $table->json('allowed_sizes');                  // ["250ml", "500ml", "1L", "Flexitank 24000L", etc.]
            
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            // SEO & OpenGraph
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->string('og_image')->nullable();

            $table->timestamps();
        });

        // Table pivot pour les certificats rattachés aux produits
        Schema::create('certificate_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_certificate_id')->constrained('product_certificates')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_product');
        Schema::dropIfExists('products');
    }
};
