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
        Schema::create('product_certificates', function (Blueprint $table) {
            $table->id();
            $table->json('name');           // {"ar": "...", "en": "...", "fr": "..."}
            $table->string('issuer');       // Ex: Ecocert, FDA, COI, ISO
            $table->string('badge_icon')->nullable();
            $table->string('pdf_file_path')->nullable();
            $table->date('valid_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_certificates');
    }
};
