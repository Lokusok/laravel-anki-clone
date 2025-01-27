<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deck extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
    ];

    public function scopeByTitle(Builder $query, ?string $title): void
    {
        if (! empty($title)) {
            $query->where('title', 'like', "%{$title}%");
        }
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'deck_id', 'id');
    }

    public function questionsAskReady(): HasMany
    {
        return $this->hasMany(Question::class, 'deck_id', 'id')
            ->where('when_ask', '<', now());
    }

    public function questionsAskLater(): HasMany
    {
        return $this->hasMany(Question::class, 'deck_id', 'id')
            ->where('when_ask', '>', now());
    }
}
