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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_request_id')->constrained()->cascadeOnDelete();
            $table->string('contract_number')->unique(); // ex: CNT-2026-0001
            $table->string('pdf_path')->nullable();
            
            // Conditions Commerciales et Bancaires
            $table->text('payment_terms');
            $table->json('bank_details'); // bank_name, iban, swift, account_holder
            $table->json('required_documents')->nullable(); // EUR1, Phyto, Origin, COI Lab Report
            
            // Expédition DHL de l'original signé
            $table->string('dhl_tracking_number')->nullable();
            $table->string('signed_copy_path')->nullable();
            
            $table->enum('status', ['draft', 'issued', 'signed_by_client', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contract_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_number')->unique(); // ex: INV-2026-0001
            $table->enum('invoice_type', ['proforma', 'commercial'])->default('proforma');
            $table->string('pdf_path')->nullable();
            $table->decimal('subtotal', 14, 2);
            $table->decimal('freight_cost', 12, 2)->default(0);
            $table->decimal('insurance_cost', 12, 2)->default(0);
            $table->decimal('total_amount', 14, 2);
            $table->string('currency', 3)->default('EUR');
            $table->enum('status', ['unpaid', 'partially_paid', 'paid', 'cancelled'])->default('unpaid');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('contracts');
    }
};
