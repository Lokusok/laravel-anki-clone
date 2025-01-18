<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Deck;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Request $request, Deck $deck, QuestionRepository $repository)
    {
        $tag = $request->query('tag');

        $questions = $repository->findByTag([
            'deck_id' => $deck->id,
            'tag' => $tag
        ]);

        return QuestionResource::collection($questions);
    }

    public function store(StoreQuestionRequest $request, Deck $deck, QuestionRepository $repository)
    {
        $data = $request->all();

        $data['deck_id'] = $deck->id;

        $question = $repository->create($data);

       return QuestionResource::make($question)->response()->setStatusCode(201);
    }

    public function update(UpdateQuestionRequest $request, Deck $deck,  string $questionId)
    {
        $data = $request->validated();

        $question = $deck->questions()->where('id', $questionId)->first();

        abort_if(! $question, 404, "Вопрос с ID {$questionId} не существует");

        $question->update($data);

        return QuestionResource::make($question);
    }

    public function destroy(Deck $deck, string $questionId)
    {
        $deck->questions()->where('id', $questionId)->delete($questionId);

        return response()->noContent();
    }
}
