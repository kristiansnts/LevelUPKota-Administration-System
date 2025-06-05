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
        Schema::create('qr_generator_qr_signer', function (Blueprint $table) {
            $table->ulid('qr_generator_qr_signer_id')->primary();
            $table->ulid('qr_generator_id');
            $table->ulid('qr_signer_id')->nullable();
            $table->boolean('is_sign')->default(false);

            $table->foreign('qr_generator_id')->references('qr_id')->on('qr_generator');
            $table->foreign('qr_signer_id')->references('qr_signer_id')->on('qr_signer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_generator_qr_signer');
    }
};
