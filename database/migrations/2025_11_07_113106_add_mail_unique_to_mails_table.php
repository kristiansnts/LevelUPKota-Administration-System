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
        Schema::table('mails', function (Blueprint $table) {
            $table->ulid('mail_unique')->nullable()->after('id');
        });

        // Populate existing records with ULID
        \DB::table('mails')->whereNull('mail_unique')->orWhere('mail_unique', '')->chunkById(100, function ($mails) {
            foreach ($mails as $mail) {
                \DB::table('mails')->where('id', $mail->id)->update([
                    'mail_unique' => (string) \Illuminate\Support\Str::ulid()
                ]);
            }
        });

        // Make the column unique after populating
        Schema::table('mails', function (Blueprint $table) {
            $table->ulid('mail_unique')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mails', function (Blueprint $table) {
            $table->dropColumn('mail_unique');
        });
    }
};
