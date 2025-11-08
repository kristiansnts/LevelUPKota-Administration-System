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
        Schema::table('qr_signer', function (Blueprint $table) {
            // Check if columns don't already exist
            if (!Schema::hasColumn('qr_signer', 'city_id')) {
                $table->unsignedBigInteger('city_id')->nullable()->after('phone_number');
            }
            
            if (!Schema::hasColumn('qr_signer', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('city_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_signer', function (Blueprint $table) {
            if (Schema::hasColumn('qr_signer', 'city_id')) {
                $table->dropColumn('city_id');
            }
            
            if (Schema::hasColumn('qr_signer', 'district_id')) {
                $table->dropColumn('district_id');
            }
        });
    }
};
