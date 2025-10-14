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
        Schema::create('purchases', function (Blueprint $table) {
             $table->id();
             $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
             $table->string('invoice_no')->unique();
             $table->decimal('total', 10, 2);
             $table->decimal('paid', 10, 2)->default(0);
             $table->decimal('due', 10, 2)->default(0);
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
