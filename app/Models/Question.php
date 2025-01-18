<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function stat(): HasOne
    {
        return $this->hasOne(Stat::class, 'question_id', 'id');
    }
}
