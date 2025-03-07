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
        Schema::create('finance_user', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('finance_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('finance_id')->references('id')->on('finances');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_user');
    }
};
