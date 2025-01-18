<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Deck;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Deck $deck)
    {
        $questions = $deck->questions()->orderBy('created_at', 'DESC')->get();
        return QuestionResource::collection($questions);
    }

    public function store(StoreQuestionRequest $request, Deck $deck)
    {
        $data = $request->all();

        $question = $deck->questions()->create($data);

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
