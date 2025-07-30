<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ValidNik implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[0-9]{16}$/', $value)) {
            $fail('NIK harus terdiri dari 16 digit angka.');
            return;
        }

        // =================================================================
        // REVISI DI SINI: Bentuk kode wilayah dengan format yang benar (pakai titik)
        // =================================================================
        $provinceCode = substr($value, 0, 2);
        $regencyCode  = substr($value, 0, 2) . '.' . substr($value, 2, 2); // Contoh: 35.78
        $districtCode = substr($value, 0, 2) . '.' . substr($value, 2, 2) . '.' . substr($value, 4, 2); // Contoh: 35.78.01

        // 1. Cek Kode Provinsi
        if (!DB::table('provinces')->where('code', $provinceCode)->exists()) {
            $fail('Kode provinsi pada NIK tidak valid.');
            return;
        }

        // 2. Cek Kode Kota/Kabupaten
        if (!DB::table('regencies')->where('code', $regencyCode)->exists()) {
            $fail('Kode kota/kabupaten pada NIK tidak valid.');
            return;
        }
        
        // 3. Cek Kode Kecamatan
        if (!DB::table('districts')->where('code', $districtCode)->exists()) {
            $fail('Kode kecamatan pada NIK tidak valid.');
            return;
        }

        // 4. Cek Tanggal Lahir
        $day = (int) substr($value, 6, 2);
        $month = (int) substr($value, 8, 2);
        $yearSuffix = (int) substr($value, 10, 2);
        
        if ($day > 40) {
            $day -= 40; // Kurangi 40 jika perempuan untuk mendapatkan tanggal asli
        }

        $currentYearSuffix = (int) date('y');
        $year = $yearSuffix > $currentYearSuffix ? '19' . $yearSuffix : '20' . $yearSuffix;

        if (!checkdate($month, $day, (int) $year)) {
            $fail('Tanggal lahir pada NIK tidak valid.');
        }
    }
}