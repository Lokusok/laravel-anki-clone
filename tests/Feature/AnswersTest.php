<?php

namespace Tests\Feature;

use App\Enums\AnswerType;
use App\Exceptions\QuestionToEarlyToAnswer;
use App\Models\Question;
use App\Models\User;
use Database\Seeders\DeckSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnswersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private int $questionId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $this->seed(DeckSeeder::class);
        $this->actingAs(User::first());
    }

    public function test_early_answer_reject(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(QuestionToEarlyToAnswer::class);

        $question = Question::create([
            'deck_id' => 1,
            'front' => $this->faker->sentence(2),
            'back' => $this->faker->sentence(2),
            'when_ask' => now()->addDay(),
        ]);

        $this->questionId = $question->id;

        $response = $this->post(
            route('answers.store', ['deck' => $question->deck_id, 'question' => $this->questionId]),
            [
                'type' => AnswerType::EASY->value,
            ]
        );

        $response->assertStatus(Response::HTTP_TOO_EARLY);
    }

    public function test_later_answer_accept(): void
    {
        $question = Question::create([
            'deck_id' => 1,
            'front' => $this->faker->sentence(2),
            'back' => $this->faker->sentence(2),
            'when_ask' => now()->subDay(),
        ]);

        $previosKeyCount = $question->stat->count_easy;

        $this->questionId = $question->id;

        $response = $this->post(
            route('answers.store', ['deck' => $question->deck_id, 'question' => $this->questionId]),
            [
                'type' => AnswerType::EASY->value,
            ]
        );

        $question->refresh();
        $currentKeyCount = $question->stat->count_easy;

        $this->assertGreaterThan($previosKeyCount, $currentKeyCount);

        $response->assertStatus(Response::HTTP_OK);
    }
}
