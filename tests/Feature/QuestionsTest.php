<?php

namespace Tests\Feature;

use App\Exceptions\QuestionNotFoundException;
use App\Models\Deck;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class QuestionsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private int $deckId = 1;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::first());
    }

    public function test_questions_index(): void
    {
        $response = $this->get(route('questions.index', ['deck' => $this->deckId]));

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'front',
                    'back',
                    'deck_id',
                    'tags' => [
                        '*' => [
                            'title',
                        ],
                    ],
                    'when_ask',
                    'created_at',
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_unknown_question_update_throw_404()
    {
        $this->withoutExceptionHandling();
        $this->expectException(QuestionNotFoundException::class);

        $unknownId = 9999;

        $response = $this->put(
            route('questions.update', ['deck' => $this->deckId, 'questionId' => $unknownId]),
            [
                'front' => $this->faker->sentence(2),
                'back' => $this->faker->sentence(2),
                'tags' => ['php'],
                'deck_id' => $this->deckId,
            ]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_question_store(): void
    {
        $newQuestion = [
            'front' => $this->faker->sentence(2),
            'back' => $this->faker->sentence(2),
            'tags' => ['php', 'laravel'],
        ];

        $response = $this->post(
            route('questions.store', ['deck' => $this->deckId]),
            [
                'front' => $newQuestion['front'],
                'back' => $newQuestion['back'],
                'tags' => $newQuestion['tags'],
            ]
        );

        $response->assertJsonStructure([
            'data' => [
                'id',
                'front',
                'back',
                'deck_id',
                'tags' => [
                    '*' => [
                        'title',
                    ],
                ],
                'when_ask',
                'created_at',
            ],
        ]);

        $json = $response->json();

        $question = Question::find($json['data']['id']);

        $this->assertEquals($question->front, $newQuestion['front']);
        $this->assertEquals($question->back, $newQuestion['back']);

        $tagFirst = Tag::query()->where('title', $json['data']['tags'][0]['title'])->first();
        $tagSecond = Tag::query()->where('title', $json['data']['tags'][1]['title'])->first();

        $this->assertEquals($tagFirst->title, $newQuestion['tags'][0]);
        $this->assertEquals($tagSecond->title, $newQuestion['tags'][1]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_question_update(): void
    {
        $deck = Deck::find($this->deckId);

        $questionId = $deck->questions()->create([
            'front' => $this->faker->sentence(10),
            'back' => $this->faker->sentence(10),
            'when_ask' => now()->addDay(),
        ])->id;

        $newQuestion = [
            'front' => $this->faker->sentence(2),
            'back' => $this->faker->sentence(2),
            'tags' => ['golang', 'python'],
            'deck_id' => $deck->id,
        ];

        $response = $this->put(
            route('questions.update', ['deck' => $this->deckId, 'questionId' => $questionId]),
            [
                'front' => $newQuestion['front'],
                'back' => $newQuestion['back'],
                'tags' => $newQuestion['tags'],
                'deck_id' => $newQuestion['deck_id'],
            ],
        );

        $response->assertJsonStructure([
            'data' => [
                'id',
                'front',
                'back',
                'deck_id',
                'tags' => [
                    '*' => [
                        'title',
                    ],
                ],
                'when_ask',
                'created_at',
            ],
        ]);

        $json = $response->json();

        $question = Question::find($json['data']['id']);

        $this->assertEquals($question->front, $newQuestion['front']);
        $this->assertEquals($question->back, $newQuestion['back']);

        $tagFirst = Tag::query()->where('title', $json['data']['tags'][0]['title'])->first();
        $tagSecond = Tag::query()->where('title', $json['data']['tags'][1]['title'])->first();

        $this->assertEquals($tagFirst->title, $newQuestion['tags'][0]);
        $this->assertEquals($tagSecond->title, $newQuestion['tags'][1]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_question_delete(): void
    {
        $question = Question::create([
            'deck_id' => 1,
            'front' => $this->faker->sentence(2),
            'back' => $this->faker->sentence(2),
            'when_ask' => now()->addDay(),
        ]);

        $response = $this->delete(
            route('questions.destroy',
                ['deck' => $this->deckId, 'questionId' => $question->id])
        );

        $question = Question::find($question->id);
        $this->assertNull($question);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_question_unknown_delete_throw_404(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(QuestionNotFoundException::class);

        $questionId = 9999;

        $response = $this->delete(
            route('questions.destroy',
                ['deck' => $this->deckId, 'questionId' => $questionId])
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
