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
            $table->string('submission_id')->unique(); // ID unik spt KRD-00123
            $table->foreignId('users_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
            // === Data Diri Pemohon ===
            $table->string('ktp_number');
            $table->string('ktp_photo_path');
            $table->text('address');
            $table->string('phone_number');

            // === Data Usaha Pemohon ===
            $table->string('business_name');
            $table->string('business_type');
            $table->integer('business_age_months');
            $table->bigInteger('monthly_revenue');
            $table->text('business_address');

            // === Detail Pengajuan Kredit ===
            $table->bigInteger('amount_requested');
            $table->integer('tenor_months');
            $table->string('loan_purpose');

            // === Dokumen dari Pemohon ===
            $table->string('business_photo_path');
            $table->string('business_document_path')->nullable();
            $table->string('bank_statement_path')->nullable();

            // === Kolom untuk Alur Kerja (Status & Catatan) ===
            $table->string('status')->default('Menunggu Verifikasi');
            
            // Kolom untuk Verifikator
            $table->text('verification_notes')->nullable();
            $table->string('verification_document_path')->nullable();

            // Kolom untuk Operator
            $table->text('operator_notes')->nullable();
            
            // Kolom untuk Approver
            $table->text('approver_notes')->nullable();

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
