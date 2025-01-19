<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Deck;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuestionController extends Controller
{
    public function index(Request $request, Deck $deck, QuestionRepository $repository)
    {
        Gate::authorize('viewQuestions', $deck);

        $tag = $request->query('tag');

        $questions = $repository->findByTag([
            'deck_id' => $deck->id,
            'tag' => $tag,
        ]);

        return QuestionResource::collection($questions)->additional(['test' => true]);
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
