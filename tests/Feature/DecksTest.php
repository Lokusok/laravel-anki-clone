<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Deck;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DecksTest extends TestCase
{
    use RefreshDatabase;

    private int $deckCount = 10;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_deck_index(): void
    {
        $response = $this->get(route('decks.index'));

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'user_id',
                    'created_at',
                ],
            ],
        ]);

        $response->assertStatus(200);
    }

    public function test_deck_store(): void
    {
        $randomTitle = bin2hex(random_bytes(10));

        $response = $this->post(route('decks.store'), [
            'title' => $randomTitle,
            'user_id' => 1,
        ]);

        $json = $response->json();
        $deck = Deck::find($json['data']['id']);

        $this->assertEquals($deck->title, $randomTitle);

        $response->assertStatus(201);
    }

    public function test_deck_edit()
    {
        $deckId = 1;
        $newTitle = 'updated title';

        $response = $this->put(route('decks.update', ['deck' => $deckId]), [
            'title' => $newTitle,
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'user_id',
                'created_at',
            ],
        ]);

        $deck = Deck::find($deckId);

        $this->assertEquals($deck->title, $newTitle);
        $response->assertStatus(200);
    }

    public function test_deck_delete(): void
    {
        $randomDeck = Deck::first();

        $response = $this->delete(route('decks.destroy', ['deck' => $randomDeck->id]));

        $deck = Deck::find($randomDeck->id);

        $this->assertNull($deck);
        $response->assertStatus(204);
    }

    public function test_unknown_deck_delete_throw_404(): void
    {
        $undefinedId = 9999;

        $response = $this->delete(route('decks.destroy', ['deck' => $undefinedId]));

        $response->assertStatus(404);
    }
}
