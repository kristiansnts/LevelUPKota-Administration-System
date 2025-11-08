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
        Schema::table('qr_generator_qr_signer', function (Blueprint $table) {
            $table->text('rejection_notes')->nullable()->after('total_sign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_generator_qr_signer', function (Blueprint $table) {
            $table->dropColumn('rejection_notes');
        });
    }
};
