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
        Schema::table('report_user', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['report_id']);
            
            // Re-add the foreign key with onDelete('cascade')
            // When a report is deleted, related report_user records should also be deleted
            $table->foreign('report_id')
                  ->references('id')
                  ->on('reports')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_user', function (Blueprint $table) {
            // Drop the modified foreign key
            $table->dropForeign(['report_id']);
            
            // Restore the original foreign key without onDelete
            $table->foreign('report_id')
                  ->references('id')
                  ->on('reports');
        });
    }
};
