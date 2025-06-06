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
        Schema::create('districts', function (Blueprint $table) {
            $table->id('kecamatan_id'); // Using kecamatan_id as primary key to match CSV
            $table->unsignedBigInteger('kabupaten_id');
            $table->string('kecamatan_name');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('kabupaten_id')->references('kabupaten_id')->on('cities')->onDelete('cascade');

            // Indexes for faster searches
            $table->index('kecamatan_name');
            $table->index('kabupaten_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
