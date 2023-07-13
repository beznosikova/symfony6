<?php

namespace App\EventListener;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Survey::class)]
class SurveyChangedNotifier
{
    public function prePersist(Survey $survey, PrePersistEventArgs $args): void
    {
        // todo: smt
    }
}