<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditApplication extends Model
{
    use HasFactory;

    /*

The attributes that are mass assignable.

@var array

/*

Mendefinisikan relasi bahwa setiap CreditApplication 'milik' satu User.

Ini akan menyelesaikan error "Undefined method 'user'".
*/
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
