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
        Schema::create('laboratory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('name', 100);
            $table->enum('category', ['Komputer', 'Laptop', 'Jaringan', 'Aksesoris', 'Lainnya']);
            $table->integer('quantity');
            $table->enum('status', ['Baru', 'Digunakan', 'Rusak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_items');
    }
};
