<?php

namespace App\Repositories;

use App\Exceptions\QuestionNotFoundException;
use App\Exceptions\QuestionToEarlyToAnswer;
use App\Models\Deck;
use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class QuestionRepository
{
    public function __construct(private TagRepository $tagRepository) {}

    /**
     * @param  array{front: string, back: string, when_ask: string, tags: array<int, string>}  $attributes
     */
    public function create(array $attributes): Question
    {
        $question = DB::transaction(function () use ($attributes) {
            $tagsIds = $this->tagRepository->createFromArrayUnique($attributes['tags']);

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
     * @param  array{question_id: string|int, data: array<string, mixed>}  $attributes
     */
    public function update(array $attributes): Question
    {
        $question = Question::find($attributes['question_id']);

        if (! $question) {
            throw new QuestionNotFoundException("Вопрос с ID {$attributes['question_id']} не существует");
        }

        $question->update($attributes['data']);
        $tagsIds = $this->tagRepository->createFromArrayUnique($attributes['data']['tags']);

        $question->tags()->sync($tagsIds);

        return $question;
    }

    /**
     * @param  array{deck_id: int, tag: string}  $attributes
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
     * @param  array{question_id: int, type: string}  $attributes
     */
    public function answerToQuestion(array $attributes): Question
    {
        $question = DB::transaction(function () use ($attributes) {
            $key = 'count_'.$attributes['type'];

            $question = Question::find($attributes['question_id']);

            // Не даём ответить, если не подошло время ответа
            if ($question->when_ask->greaterThan(now())) {
                throw new QuestionToEarlyToAnswer('Время ответа не подошло');
            }

            $question->stat()->increment($key);
            $toReset = [];

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
                    $whenAsk = now()->addMinutes(10 * $question->stat->count_hard);
                    $toReset = ['count_easy', 'count_good', 'count_again'];
                    break;
                case 'again':
                    $whenAsk = now()->addMinute();
                    break;
                default:
                    $whenAsk = now()->addDay();
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
