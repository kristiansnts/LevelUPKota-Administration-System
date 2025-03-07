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
        Schema::create('mail_user', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('mail_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('mail_id')->references('id')->on('mails');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_user');
    }
};
