<?php

namespace App\Repositories;

use App\Models\Deck;
use App\Models\Question;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

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

            $question->stat()->create();
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

    /**
     * @param array{question_id: int, type: string} $attributes
     */
    public function answerToQuestion(array $attributes): Question
    {
        $question = DB::transaction(function () use ($attributes) {
            $key = "count_" . $attributes['type'];

            $question = Question::find($attributes['question_id']);

            // Не даём ответить, если не подошло время ответа
            if ($question->when_ask->greaterThan(now())) {
                abort(Response::HTTP_TOO_EARLY, 'Время ответа не подошло');
            }

            $question->stat()->increment($key);

            switch ($attributes['type']) {
                case 'easy':
                    $whenAsk = now()->addDays(3 * $question->stat->count_easy);
                    $toReset = ['count_good', 'count_hard', 'count_again'];
                    break;
                case 'good':
                    $whenAsk = now()->addDays(2 * $question->stat->count_good);
                    $toReset = ['count_easy', 'count_hard', 'count_again'];
                    break;
                case 'hard':
                    $whenAsk  = now()->addMinutes(10 * $question->stat->count_hard);
                    $toReset = ['count_easy', 'count_good', 'count_again'];
                    break;
                case 'again':
                    $whenAsk = now()->addMinute();
                    break;
            }

            $toReset = collect($toReset)->mapWithKeys(function ($key) {
                return [$key => 0];
            });

            $question->update([
                'when_ask' => $whenAsk,
            ]);
            $question->stat()->update($toReset->toArray());

            return $question;
        });

        return $question;
    }
}
