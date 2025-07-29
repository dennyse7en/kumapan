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
        Schema::create('welcome_images', function (Blueprint $table) {
        $table->id();
        $table->string('path'); // Path/lokasi file gambar
        $table->string('title')->nullable(); // Judul (opsional)
        $table->string('subtitle')->nullable(); // Sub-judul (opsional)
        $table->boolean('is_active')->default(true); // Status aktif/tidak
        $table->integer('order_column')->default(0); // Urutan tampil
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welcome_images');
    }
};
