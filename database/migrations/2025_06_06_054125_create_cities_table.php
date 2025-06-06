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
        Schema::create('cities', function (Blueprint $table) {
            $table->id('kabupaten_id'); // Using kabupaten_id as primary key to match CSV
            $table->unsignedBigInteger('provinsi_id');
            $table->string('kabupaten_name');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('provinsi_id')->references('provinsi_id')->on('provinces')->onDelete('cascade');

            // Indexes for faster searches
            $table->index('kabupaten_name');
            $table->index('provinsi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
