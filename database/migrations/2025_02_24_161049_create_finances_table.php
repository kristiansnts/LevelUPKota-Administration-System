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
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->string('period');
            $table->date('transaction_date');
            $table->unsignedBigInteger('transaction_category_id');
            $table->string('description');
            $table->unsignedBigInteger('transaction_type_id');
            $table->float('amount_in');
            $table->float('amount_out');
            $table->string('invoice_code');
            $table->enum('type', ['income', 'expense']);
            $table->string('transaction_proof_link')->nullable();
            $table->timestamps();

            $table->foreign('transaction_category_id')->references('id')->on('transaction_category');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_type');
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
