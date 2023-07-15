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

    public static function choices(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }
}
