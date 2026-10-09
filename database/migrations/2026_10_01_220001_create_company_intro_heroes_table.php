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
        Schema::create('company_intro_heroes', function (Blueprint $table) {
            $table->id();
            $table->json('badge')->nullable();
            $table->json('title');
            $table->json('subtitle')->nullable();
            $table->string('background_image')->default('images/hero/company_export_hero.jpg');
            $table->json('stats')->nullable();
            $table->json('primary_cta_text')->nullable();
            $table->string('primary_cta_url')->default('/rfq');
            $table->json('secondary_cta_text')->nullable();
            $table->string('secondary_cta_url')->default('#products');
            $table->string('whatsapp_number')->nullable()->default('+21698000000');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_intro_heroes');
    }
};
