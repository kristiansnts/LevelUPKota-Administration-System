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
        Schema::create('report_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('reports');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_user');
    }
};
