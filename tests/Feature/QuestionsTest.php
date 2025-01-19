<?php

namespace Tests\Feature;

use App\Models\Deck;
use App\Models\Question;
use App\Models\Tag;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private int $deckId = 1;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testQuestionsIndex(): void
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
                            'title'
                        ]
                    ],
                    'when_ask',
                    'created_at',
                ],
            ],
        ]);

        $response->assertStatus(200);
    }

    public function testQuestionStore(): void
    {
        $newQuestion = [
            'front' => $this->faker->sentence(2),
            'back' => $this->faker->sentence(2),
            'tags' => ['php', 'laravel']
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
                        'title'
                    ]
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

        $response->assertStatus(201);
    }

    public function testQuestionUpdate(): void
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
            'tags' => ['golang', 'python']
        ];

        $response = $this->put(
            route('questions.update', ['deck' => $this->deckId, 'questionId' => $questionId]),
            [
                'front' => $newQuestion['front'],
                'back' => $newQuestion['back'],
                'tags' => $newQuestion['tags'],
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
                        'title'
                    ]
                ],
                'when_ask',
                'created_at',
            ],
        ]);

        $json = $response->json();

        $question = Question::find($json['data']['id']);

        $this->assertEquals($question->front, $newQuestion['front']);
        $this->assertEquals($question->back, $newQuestion['back']);

        // @TODO Исправить
        $tagFirst = Tag::query()->where('title', $json['data']['tags'][0]['title'])->first();
        $tagSecond = Tag::query()->where('title', $json['data']['tags'][1]['title'])->first();

        $this->assertEquals($tagFirst->title, $newQuestion['tags'][0]);
        $this->assertEquals($tagSecond->title, $newQuestion['tags'][1]);

        $response->assertStatus(200);
    }

    public function testQuestionDelete(): void
    {
        $questionId = 1;

        $response = $this->delete(
                route('questions.destroy',
            ['deck'  => $this->deckId, 'questionId' => $questionId])
        );

        $question = Question::find($questionId);
        // $this->assertNull($question);

        $response->assertStatus(204);
    }
}
