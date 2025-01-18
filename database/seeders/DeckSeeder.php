<?php

namespace Database\Seeders;

use App\Models\Deck;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeckSeeder extends Seeder
{
    public function run(): void
    {
        Deck::factory(10)->create();
    }
}
