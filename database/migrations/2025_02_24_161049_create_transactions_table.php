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
        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->date('transaction_date');
            $table->unsignedBigInteger('transaction_category_id');
            $table->string('description');
            $table->unsignedBigInteger('payment_method_id');
            $table->float('amount');
            $table->float('balance')->default(0);
            $table->string('invoice_code');
            $table->string('transaction_proof_link')->nullable();
            $table->timestamps();

            $table->foreign('transaction_category_id')->references('id')->on('transaction_categories');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
