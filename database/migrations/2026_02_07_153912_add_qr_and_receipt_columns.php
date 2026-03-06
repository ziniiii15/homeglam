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
            if (!Schema::hasColumn('beauticians', 'qr_code_path')) {
                $table->string('qr_code_path')->nullable()->after('photo_url');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'payment_receipt_path')) {
                $table->string('payment_receipt_path')->nullable()->after('payment_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beauticians', function (Blueprint $table) {
            $table->dropColumn('qr_code_path');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_receipt_path');
        });
    }
};
