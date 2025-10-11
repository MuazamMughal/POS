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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('customer_id')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->string('discount_reason')->nullable();
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->enum('payment_method', ['cash', 'credit_card', 'debit_card', 'bank_transfer']);
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('change_returned', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('sold_by');
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
