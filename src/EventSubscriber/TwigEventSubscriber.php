<?php

namespace App\EventSubscriber;

use App\Repository\SurveyRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private Environment $twig, private SurveyRepository $surveyRepository)
    {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $this->twig->addGlobal('surveys', $this->surveyRepository->findAll());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
