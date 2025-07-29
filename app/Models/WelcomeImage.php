<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeImage extends Model
{
    protected $fillable = [
        'path',
        'title',
        'subtitle',
        'is_active',
        'order_column',
    ];
}
