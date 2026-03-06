<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('beauticians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo_url')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->string('experience');
            $table->string('base_location');
            $table->decimal('booking_fee', 8, 2);
            $table->string('password');
            $table->json('services')->nullable(); // ✅ included here
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauticians');
    }
};
