<?php

namespace App\Observers;

use App\Models\Question;

class QuestionObserver
{
    public function created(Question $question): void
    {
        $question->stat()->create();
    }
}
