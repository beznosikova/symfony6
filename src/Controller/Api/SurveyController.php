<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SurveyController extends AbstractController
{
    #[Route('survey/{survey}', name: 'survey', methods: ["GET"])]
    public function showSurvey(Survey $survey): JsonResponse
    {
        return $this->json($survey);
    }

    #[Route('survey-questions/{survey}', name: 'survey.questions', methods: ["GET"])]
    public function showQuestions(Survey $survey): JsonResponse
    {
        $questions = $survey->getQuestions();
        return $this->json($questions);
    }

    #[Route('survey-questions-options/{survey}/{question}', name: 'survey.questions.options', methods: ["GET"])]
    public function showOptions(Survey $survey, Question $question): JsonResponse
    {
        $options = $question->getOptions();
        return $this->json($options);
    }
}
