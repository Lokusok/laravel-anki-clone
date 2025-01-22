<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\StrCleaner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Deck;
use App\Models\Question;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class QuestionController extends Controller
{
    public function index(Request $request, Deck $deck, QuestionRepository $repository)
    {
        Gate::authorize('viewQuestions', $deck);

        $tag = $request->query('tag');
        $isAskReady = $request->query('ask_ready');
        $isShuffle = $request->query('shuffle');

        $questions = $repository->findByTag([
            'deck_id' => $deck->id,
            'tag' => $tag,
            'isAskReady' => $isAskReady === 'true',
            'isShuffle' => $isShuffle === 'true',
        ]);

        return QuestionResource::collection($questions);
    }

    public function show(Deck $deck, Question $question)
    {
        Gate::authorize('viewQuestions', $deck);

        return QuestionResource::make($question);
    }

    public function search(Request $request, QuestionRepository $repository)
    {
        $tag = $request->query('tag', '');
        $deckId = $request->query('deck_id', '');
        $query = $request->query('query');

        $questions = $repository->searchBy([
            'tag' => StrCleaner::arraifyCommasSequence($tag),
            'deck_id' => StrCleaner::arraifyCommasSequence($deckId),
            'query' => $query,
        ]);

        return QuestionResource::collection($questions);
    }

    public function store(StoreQuestionRequest $request, Deck $deck, QuestionRepository $repository)
    {
        Gate::authorize('storeQuestion', $deck);

        $data = $request->all();

        $data['deck_id'] = $deck->id;

        $question = $repository->create($data);

        return QuestionResource::make($question)->response()->setStatusCode(201);
    }

    public function update(UpdateQuestionRequest $request, Deck $deck, string $questionId, QuestionRepository $repository)
    {
        Gate::authorize('updateQuestion', $deck);

        $data = $request->validated();

        $toDeck = Deck::find($data['deck_id']);

        Gate::authorize('update', $toDeck);

        $question = $repository->update([
            'question_id' => $questionId,
            'data' => $data,
        ]);

        return QuestionResource::make($question);
    }

    public function destroy(Deck $deck, string $questionId, QuestionRepository $repository)
    {
        Gate::authorize('deleteQuestion', $deck);

        $repository->delete(['question_id' => $questionId]);

        return response()->noContent();
    }
}
