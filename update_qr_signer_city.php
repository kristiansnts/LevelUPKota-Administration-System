<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Updating QR Signer City/District IDs ===\n\n";

// Get the most common city_id and district_id from mails table
$mailCityData = DB::table('mails')
    ->select('city_id', 'district_id', DB::raw('COUNT(*) as count'))
    ->whereNotNull('city_id')
    ->groupBy('city_id', 'district_id')
    ->orderBy('count', 'desc')
    ->first();

if (!$mailCityData) {
    echo "❌ No mail records with city_id found. Trying to get from users...\n";
    
    // Fallback: get from users table
    $userCityData = DB::table('users')
        ->select('city_id', 'district_id', DB::raw('COUNT(*) as count'))
        ->whereNotNull('city_id')
        ->groupBy('city_id', 'district_id')
        ->orderBy('count', 'desc')
        ->first();
    
    if (!$userCityData) {
        echo "❌ No users with city_id found. Cannot auto-populate.\n";
        exit(1);
    }
    
    $cityId = $userCityData->city_id;
    $districtId = $userCityData->district_id;
} else {
    $cityId = $mailCityData->city_id;
    $districtId = $mailCityData->district_id;
}

echo "📍 Detected City ID: {$cityId}\n";
echo "📍 Detected District ID: {$districtId}\n\n";

// Get all qr_signers without city_id
$signersToUpdate = DB::table('qr_signer')
    ->whereNull('city_id')
    ->count();

echo "Found {$signersToUpdate} QR Signers to update\n";

if ($signersToUpdate === 0) {
    echo "✅ All QR Signers already have city_id set!\n";
    exit(0);
}

// Ask for confirmation
echo "\nThis will update {$signersToUpdate} records.\n";
echo "Do you want to continue? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));

if (strtolower($line) !== 'yes') {
    echo "❌ Cancelled.\n";
    exit(0);
}

// Update qr_signer records
echo "\nUpdating QR Signers...\n";
$updated = DB::table('qr_signer')
    ->whereNull('city_id')
    ->update([
        'city_id' => $cityId,
        'district_id' => $districtId,
        'updated_at' => now(),
    ]);

echo "✅ Updated {$updated} QR Signer records\n\n";

// Show summary
echo "=== Summary ===\n";
echo "City ID: {$cityId}\n";
echo "District ID: {$districtId}\n";
echo "QR Signers Updated: {$updated}\n";
echo "\n✅ Done!\n";
