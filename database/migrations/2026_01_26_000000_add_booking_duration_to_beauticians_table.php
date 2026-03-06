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
        Schema::table('beauticians', function (Blueprint $table) {
            if (!Schema::hasColumn('beauticians', 'booking_duration')) {
                $table->integer('booking_duration')->default(60); // Default 60 minutes
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beauticians', function (Blueprint $table) {
            $table->dropColumn('booking_duration');
        });
    }
};
