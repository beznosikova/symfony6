<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class SurveyStatus extends Constraint
{
    public string $message = 'Survey can not be changed on status READY "{{ value }}"';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
