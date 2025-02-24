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
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->string('mail_code');
            $table->date('mail_date');
            $table->unsignedBigInteger('mail_category_id');
            $table->string('sender_name');
            $table->string('receiver_name');
            $table->string('description');
            $table->enum('type', ['in', 'out']);
            $table->string('link');
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('mail_category_id')->references('id')->on('mail_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mails');
    }
};
