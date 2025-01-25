<?php

namespace App\Enums;

enum AnswerType: string
{
    case EASY = 'easy';
    case GOOD = 'good';
    case HARD = 'hard';
    case AGAIN = 'again';
}
