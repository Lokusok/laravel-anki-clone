<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeckRequest;
use App\Http\Requests\UpdateDeckRequest;
use App\Http\Resources\DeckResource;
use App\Models\Deck;
use App\Repositories\DeckRepository;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    public function index()
    {
        $decks = Deck::orderBy('created_at', 'DESC')->get();
        return DeckResource::collection($decks);
    }

    public function store(StoreDeckRequest $request, DeckRepository $repository)
    {
        $data = $request->validated();

        $deck = $repository->create($data);

        return DeckResource::make($deck)->response()->setStatusCode(201);
    }

    public function update(UpdateDeckRequest $request, Deck $deck)
    {
        $data = $request->validated();

        $deck->update($data);

        return DeckResource::make($deck);
    }

    public function destroy(Deck $deck)
    {
        $deck->delete();

        return response()->noContent();
    }
}
