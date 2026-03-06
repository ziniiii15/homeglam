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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->change();
            $table->unsignedBigInteger('service_id')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->decimal('total_cost', 10, 2)->nullable()->change();
        });

        // Update status enum to include 'available' and ensure 'accepted' is present
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'accepted', 'completed', 'canceled', 'available') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // We cannot easily revert nullability if there are null values, but we can try
            // This is just a best-effort down
            //$table->unsignedBigInteger('client_id')->nullable(false)->change(); 
        });
    }
};
