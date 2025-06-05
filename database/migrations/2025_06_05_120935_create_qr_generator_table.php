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
        Schema::create('qr_generator', function (Blueprint $table) {
            $table->ulid('qr_id')->primary();
            $table->unsignedBigInteger('document_id');

            $table->foreign('document_id')->references('id')->on('mails');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_generator');
    }
};
