<?php

namespace App\Models;

use App\Observers\QuestionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([QuestionObserver::class])]
class Question extends Model
{
    use HasFactory;

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
