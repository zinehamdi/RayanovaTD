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
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->json('title');          // {"ar": "...", "en": "...", "fr": "..."}
            $table->json('subtitle')->nullable();
            $table->json('badge_text')->nullable();
            $table->json('cta_text')->nullable();
            $table->string('cta_url')->default('/products');
            $table->enum('media_type', ['image', 'video'])->default('image');
            $table->string('media_path');
            $table->decimal('overlay_opacity', 3, 2)->default(0.45);
            $table->json('glass_card_content')->nullable(); // Points clés sous verre dépoli
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
