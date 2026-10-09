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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            
            // Configuration de conditionnement choisie
            $table->string('packaging_type'); // ex: bulk, bottle_glass_marasca, tin_can
            $table->string('size');           // ex: 500ml, 1L, Flexitank 24000L
            $table->decimal('quantity', 12, 2);
            $table->string('unit')->default('units'); // units, cartons, pallets, liters, metric_tons
            
            // Chiffrage par l'Admin
            $table->decimal('offered_unit_price', 12, 2)->nullable();
            $table->decimal('subtotal', 14, 2)->nullable();
            
            // Marque Blanche / Private Label
            $table->boolean('is_private_label')->default(false);
            $table->text('custom_branding_notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
