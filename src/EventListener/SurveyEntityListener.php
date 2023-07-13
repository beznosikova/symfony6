<?php

namespace App\EventListener;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Survey::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Survey::class)]
class SurveyEntityListener
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function prePersist(Survey $conference, LifecycleEventArgs $event)
    {
        $conference->computeSlug($this->slugger);
    }

    public function preUpdate(Survey $conference, LifecycleEventArgs $event)
    {
        if ($event->hasChangedField('name')) {
            $conference->computeSlug($this->slugger);
        }
    }
}