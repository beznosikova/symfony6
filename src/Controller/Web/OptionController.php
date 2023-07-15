<?php

namespace App\Controller\Web;

use App\Entity\Question;
use App\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/survey/question-options')]
class OptionController extends AbstractController
{
    #[Route('/{slug}/{question}', name: 'question.options', methods: ['GET', 'HEAD'])]
    public function index(Survey $survey, Question $question): Response
    {
        dd('question.options', $survey, $question);
        return $this->render('question/index.html.twig', [
            'survey' => $survey,
        ]);
    }
}
