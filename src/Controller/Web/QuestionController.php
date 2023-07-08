<?php

namespace App\Controller\Web;

use App\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/survey/questions/{survey}', name: 'survey.questions', methods: ['GET', 'HEAD'])]
    public function index(Survey $survey): Response
    {
        dd('survey.questions', $survey);
        return $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }
}
