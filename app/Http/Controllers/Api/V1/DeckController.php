<?php

namespace App\Http\Controllers\Api\V1;

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
        $decks = $request->user()->decks;

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
        if (! Gate::allows('update', $deck)) {
            abort(404, 'Такой коллекции не существует');
        }

        $data = $request->validated();

        $deck->update($data);

        return DeckResource::make($deck);
    }

    public function destroy(Request $request, Deck $deck)
    {
        if (! Gate::allows('delete', $deck)) {
            abort(404, 'Такой коллекции не существует');
        }

        $deck->delete();

        return response()->noContent();
    }
}
