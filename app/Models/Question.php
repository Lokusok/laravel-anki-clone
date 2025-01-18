<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    protected $fillable = [
        'front',
        'back',
        'when_ask',
        'deck_id',
    ];

    protected $casts = [
        'when_ask' => 'datetime'
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_question', 'question_id', 'tag_id');
    }
}
