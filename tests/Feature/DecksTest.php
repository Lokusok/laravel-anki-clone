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

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function testDeckIndex(): void
    {
        $response = $this->get(route('decks.index'));

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'user_id',
                    'created_at'
                ]
            ]
        ]);

        $response->assertStatus(200);
    }

    public function testDeckStore(): void
    {
        $randomTitle = bin2hex(random_bytes(10));

        $response = $this->post(route('decks.store'), [
            'title' => $randomTitle,
            'user_id' => 1
        ]);

        $json = $response->json();
        $deck = Deck::find($json['data']['id']);

        $this->assertEquals($deck->title, $randomTitle);

        $response->assertStatus(201);
    }

    public function testDeckEdit()
    {
        $deckId = 1;
        $newTitle = 'updated title';

        $response = $this->put(route('decks.update', ['deck' => $deckId]), [
            'title' => $newTitle
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'user_id',
                'created_at',
            ]
        ]);

        $deck = Deck::find($deckId);

        $this->assertEquals($deck->title, $newTitle);
        $response->assertStatus(200);
    }

    public function testDeckDelete(): void
    {
        $randomId = rand(1, 10);

        $response = $this->delete(route('decks.destroy', ['deck' => $randomId]));

        $decks = Deck::all();

        $this->assertEquals($this->deckCount - 1, $decks->count());
        $response->assertStatus(204);
    }

    public function testUnknownDeckDeleteThrow_404(): void
    {
        $undefinedId = 9999;

        $response = $this->delete(route('decks.destroy', ['deck' => $undefinedId]));

        $response->assertStatus(404);
    }
}
