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
        Schema::create('credit_applications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Bagian: Data Diri (Contoh)
        $table->string('full_name');
        $table->string('nik')->unique();
        $table->string('phone_number');
        $table->text('address');

        // Bagian: Data Usaha (Contoh)
        $table->string('business_name');
        $table->text('business_address');
        $table->string('business_type');

        // Bagian: Detail Pengajuan
        $table->decimal('amount', 15, 2); // Jumlah pinjaman
        $table->integer('tenor'); // Jangka waktu dalam bulan

        // Bagian: Upload Dokumen
        $table->string('ktp_path');
        $table->string('business_photo_path');

        // Status Pengajuan
        $table->string('status')->default('Menunggu Verifikasi');
        $table->text('notes')->nullable(); // Untuk catatan dari tim internal

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_applications');
    }
};
