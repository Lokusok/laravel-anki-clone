<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\StrCleaner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Deck\StoreDeckRequest;
use App\Http\Requests\Deck\UpdateDeckRequest;
use App\Http\Resources\DeckResource;
use App\Models\Deck;
use App\Repositories\DeckRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeckController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('title');

        $decks = $request->user()->decks()->byTitle($search)->get();

        return DeckResource::collection($decks);
    }

    public function show(Deck $deck)
    {
        Gate::authorize('show', $deck);

        return DeckResource::make($deck);
    }

    public function search(Request $request, DeckRepository $repository)
    {
        $deckId = $request->query('deck_id', '');

        $decks = $repository->findById([
            'deck_id' => StrCleaner::arraifyCommasSequence($deckId),
        ]);

        return DeckResource::collection($decks);
    }

    public function store(StoreDeckRequest $request, DeckRepository $repository)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $deck = $repository->create($data);

        return DeckResource::make($deck)->response()->setStatusCode(201);
    }

    public function update(UpdateDeckRequest $request, Deck $deck)
    {
        Gate::authorize('update', $deck);

        $data = $request->validated();

        $deck->update($data);

        return DeckResource::make($deck);
    }

    public function destroy(Request $request, Deck $deck)
    {
        Gate::authorize('delete', $deck);

        $deck->delete();

        return response()->noContent();
    }
}
