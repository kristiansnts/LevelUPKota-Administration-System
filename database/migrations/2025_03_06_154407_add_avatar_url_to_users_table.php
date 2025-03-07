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
        Schema::table('users', function (Blueprint $table): void {
            /**
             * @var string
             */
            $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
            $table->string($avatarColumn)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            /**
             * @var string
             */
            $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
            $table->dropColumn($avatarColumn);
        });
    }
};
