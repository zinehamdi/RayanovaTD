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
        Schema::create('quotation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique(); // ex: RFQ-2026-0001
            $table->enum('request_type', ['quote', 'sample_request', 'private_label'])->default('quote');
            
            // Données Entreprise & Importateur
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('country');
            $table->string('tax_number')->nullable();
            $table->string('website')->nullable();
            
            // Modalités Commerciales Internationales (Incoterms 2020)
            $table->enum('incoterm', ['FOB', 'CIF', 'CFR', 'EXW'])->default('FOB');
            $table->string('destination_port');
            $table->enum('shipping_method', ['sea_fcl', 'sea_lcl', 'air'])->default('sea_fcl');
            $table->string('currency', 3)->default('EUR');
            
            // State Machine
            $table->enum('status', [
                'new', 
                'quoted', 
                'client_viewed', 
                'client_modified', 
                'confirmed', 
                'contract_issued', 
                'closed', 
                'cancelled'
            ])->default('new');
            
            $table->date('valid_until')->nullable(); // Date d'expiration de la cotation
            $table->string('signed_token', 64)->unique(); // Token cryptographique pour Magic Link
            $table->timestamp('last_viewed_at')->nullable();
            
            $table->text('client_notes')->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->decimal('total_amount', 14, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_requests');
    }
};
