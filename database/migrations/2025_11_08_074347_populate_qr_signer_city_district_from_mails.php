<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mailCityData = null;
        
        // Try to get city data from mails table (if columns exist)
        try {
            $mailCityData = DB::table('mails')
                ->select('city_id', 'district_id', DB::raw('COUNT(*) as count'))
                ->whereNotNull('city_id')
                ->groupBy('city_id', 'district_id')
                ->orderBy('count', 'desc')
                ->first();
        } catch (\Exception $e) {
            // Column might not exist in this environment, try users table
        }

        // If no mail data, try to get from users table
        if (!$mailCityData) {
            try {
                $mailCityData = DB::table('users')
                    ->select('city_id', 'district_id', DB::raw('COUNT(*) as count'))
                    ->whereNotNull('city_id')
                    ->groupBy('city_id', 'district_id')
                    ->orderBy('count', 'desc')
                    ->first();
            } catch (\Exception $e) {
                // Column might not exist, skip
            }
        }

        // If we found city data, update all qr_signers without city_id
        if ($mailCityData) {
            try {
                $updated = DB::table('qr_signer')
                    ->whereNull('city_id')
                    ->update([
                        'city_id' => $mailCityData->city_id,
                        'district_id' => $mailCityData->district_id,
                        'updated_at' => now(),
                    ]);
                    
                echo "✅ Updated {$updated} QR Signers with city_id: {$mailCityData->city_id}, district_id: {$mailCityData->district_id}\n";
            } catch (\Exception $e) {
                echo "⚠️ Could not update qr_signer: " . $e->getMessage() . "\n";
            }
        } else {
            echo "⚠️ No city/district data found. Skipping migration...\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set city_id and district_id back to null
        DB::table('qr_signer')
            ->update([
                'city_id' => null,
                'district_id' => null,
            ]);
    }
};
