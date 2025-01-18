<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'front',
        'back',
        'when_ask',
    ];

    protected $casts = [
        'when_ask' => 'datetime'
    ];
}
