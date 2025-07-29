<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditApplication extends Model
{
    use HasFactory;

    // Izinkan pengisian massal untuk kolom-kolom ini
    protected $guarded = ['id'];

    /**
     * Mendapatkan user yang memiliki pengajuan kredit ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
