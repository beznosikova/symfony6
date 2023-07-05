<?php

namespace App\Enums;

enum QuestionType: string
{
    case SINGLE = 'single';
    case MULTIPLE = 'multiple';

    public function text(): string
    {
        return match ($this) {
            self::SINGLE => 'Jednokrotnego wyboru',
            self::MULTIPLE => 'Wielokrotnego wyboru',
        };
    }
}
