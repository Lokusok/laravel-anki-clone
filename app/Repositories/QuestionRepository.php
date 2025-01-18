<?php

namespace App\Repositories;

use App\Models\Deck;
use App\Models\Question;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class QuestionRepository
{
    public function create(array $attributes): Question
    {
        $question = DB::transaction(function () use ($attributes) {
            $tagsIds = collect([]);

            collect($attributes['tags'])->each(function ($tag) use ($tagsIds) {
                $normalizeTag = strtolower($tag);

                $foundedTag = Tag::query()->where('title', $normalizeTag)->first();

                if (! $foundedTag) {
                    $foundedTag = Tag::create(['title' => $normalizeTag]);
                }

                $tagsIds->push($foundedTag->id);
            });

            $question = Question::create([
                'front' => $attributes['front'],
                'back' => $attributes['back'],
                'when_ask' => $attributes['when_ask'],
                'deck_id' => $attributes['deck_id'],
            ]);
            $question->tags()->sync($tagsIds);

            return $question;
        });

        return $question;
    }

    /**
     * @param array{deck_id: int, tag: string} $attributes
     */
    public function findByTag(array $attributes): Collection
    {
        $deck = Deck::find($attributes['deck_id']);

        $questions = $deck
            ->questions()
            ->whereHas('tags', function ($query) use ($attributes) {
                if ($attributes['tag']) {
                    $query->where('title', $attributes['tag']);
                }
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        return $questions;
    }
}
