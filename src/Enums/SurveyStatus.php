<?php

namespace App\Enums;

enum SurveyStatus: string
{
    case EDIT = 'edit';
    case TEST = 'test';
    case READY = 'ready';

    public function text(): string
    {
        return match ($this) {
            self::EDIT => 'Edycja',
            self::TEST => 'Testowanie',
            self::READY => 'Gotowe',
        };
    }

    public static function choices(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }
}
