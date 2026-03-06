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
        // Normalize day_of_week to lowercase to ensure consistency
        DB::table('availabilities')->update(['day_of_week' => DB::raw('LOWER(day_of_week)')]);

        // 1. Drop the foreign key that depends on the index
        try {
            Schema::table('availabilities', function (Blueprint $table) {
                $table->dropForeign(['beautician_id']);
            });
        } catch (\Exception $e) {
            // Foreign key might not exist, continue
        }

        // 2. Now drop the incorrect unique index
        try {
            Schema::table('availabilities', function (Blueprint $table) {
                $table->dropUnique('uniq_beautician');
            });
        } catch (\Exception $e) {
            // Unique index might not exist, continue
        }

        // 3. Add the correct composite unique index
        try {
            Schema::table('availabilities', function (Blueprint $table) {
                $table->unique(['beautician_id', 'day_of_week'], 'availabilities_beautician_day_unique');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }

        // 4. Re-add the foreign key
        try {
            Schema::table('availabilities', function (Blueprint $table) {
                $table->foreign('beautician_id')
                      ->references('id')
                      ->on('beauticians')
                      ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropUnique('availabilities_beautician_day_unique');
            // We don't restore the buggy 'uniq_beautician' index because it was wrong.
        });
    }
};
