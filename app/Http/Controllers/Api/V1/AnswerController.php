<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Answers\StoreAnswerRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Deck;
use App\Repositories\QuestionRepository;
use Illuminate\Support\Facades\Gate;

class AnswerController extends Controller
{
    public function store(StoreAnswerRequest $request, Deck $deck, QuestionRepository $repository, string $questionId)
    {
        if (! Gate::allows('answer', $deck)) {
            abort(404, 'Такой коллекции не существует');
        }

        $data = $request->validated();
        $data['question_id'] = $questionId;

        $question = $repository->answerToQuestion($data);

        return QuestionResource::make($question);
    }
}
