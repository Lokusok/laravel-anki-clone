<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Answers\StoreAnswerRequest;
use App\Http\Resources\QuestionResource;
use App\Repositories\QuestionRepository;

class AnswerController extends Controller
{
    public function store(StoreAnswerRequest $request, QuestionRepository $repository, string $questionId)
    {
        $data = $request->validated();
        $data['question_id'] = $questionId;

        $question = $repository->answerToQuestion($data);

        return QuestionResource::make($question);
    }
}
