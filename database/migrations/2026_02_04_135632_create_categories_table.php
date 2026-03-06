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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('icon')->nullable(); // For UI icons (e.g. 'scissors', 'lipstick')
            $table->timestamps();
        });

        // Insert default categories
        $categories = [
            ['name' => 'Hair', 'slug' => 'hair', 'icon' => 'scissors'],
            ['name' => 'Makeup', 'slug' => 'makeup', 'icon' => 'palette'],
            ['name' => 'Nails', 'slug' => 'nails', 'icon' => 'hand-index-thumb'],
            ['name' => 'Skincare', 'slug' => 'skincare', 'icon' => 'stars'],
            ['name' => 'Massage', 'slug' => 'massage', 'icon' => 'emoji-smile'],
        ];

        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
