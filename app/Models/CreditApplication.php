<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CreditApplication extends Model
{
    use HasFactory;

    // Izinkan pengisian massal untuk kolom-kolom ini
    protected $guarded = ['id'];

    protected $casts = [
        'approved_at' => 'datetime', // Pastikan kolom ini diperlakukan sebagai objek tanggal
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        // Event ini berjalan SEBELUM data baru disimpan ke database
        static::creating(function ($application) {
            do {
                // Generate 6 digit kode alfanumerik acak
                $code = Str::upper(Str::random(6));
            } while (static::where('tracking_code', $code)->exists()); // Ulangi jika kode sudah ada

            $application->tracking_code = $code;
        });
    }

    /**
     * Mendapatkan user yang memiliki pengajuan kredit ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(CreditApplicationHistory::class);
    }
}
