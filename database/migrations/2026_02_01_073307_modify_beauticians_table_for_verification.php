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
            $table->dropColumn(['experience', 'booking_fee']);
            $table->string('verification_document')->nullable();
            $table->boolean('is_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beauticians', function (Blueprint $table) {
            $table->string('experience')->nullable(); // Making nullable to avoid issues on rollback if data existed
            $table->decimal('booking_fee', 8, 2)->nullable();
            $table->dropColumn(['verification_document', 'is_verified']);
        });
    }
};
