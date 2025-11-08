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
            // Change is_sign from boolean to enum
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft')->after('qr_signer_id');
            $table->dropColumn('is_sign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_generator_qr_signer', function (Blueprint $table) {
            $table->boolean('is_sign')->default(false)->after('qr_signer_id');
            $table->dropColumn('status');
        });
    }
};
