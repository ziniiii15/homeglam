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
        // Modify payment_status to be a string to accept 'pending_verification'
        // Using raw SQL to bypass potential Doctrine DBAL missing dependency for column modification
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status VARCHAR(255) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ideally we would revert to the previous state, but since we don't know the exact previous enum definition
        // and reverting might truncate data (e.g. 'pending_verification'), we will keep it as string or 
        // attempt to revert to a common enum set if strictly required.
        // For now, leaving it as string is safer for data integrity.
    }
};
