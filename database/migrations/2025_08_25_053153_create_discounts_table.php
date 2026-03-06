<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->enum('discount_type', ['amount','percentage']);
            $table->decimal('value', 8, 2);
            $table->date('valid_from');
            $table->date('valid_to');
            $table->string('applicable_to')->nullable(); // <-- Add this line
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
