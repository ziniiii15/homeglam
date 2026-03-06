<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beauticians', function (Blueprint $table) {
            if (!Schema::hasColumn('beauticians', 'subscription_expires_at')) {
                $table->dateTime('subscription_expires_at')->nullable()->after('is_verified');
            }
        });
    }

    public function down(): void
    {
        Schema::table('beauticians', function (Blueprint $table) {
            if (Schema::hasColumn('beauticians', 'subscription_expires_at')) {
                $table->dropColumn('subscription_expires_at');
            }
        });
    }
};

