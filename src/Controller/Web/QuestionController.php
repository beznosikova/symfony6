<?php

namespace App\Controller\Web;

use App\Entity\Question;
use App\Entity\Survey;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/survey/questions')]
class QuestionController extends AbstractController
{
    #[Route('/{slug}', name: 'survey.questions', methods: ['GET', 'HEAD'])]
    public function index(Survey $survey): Response
    {
        return $this->render('question/index.html.twig', [
            'survey' => $survey,
        ]);
    }

    #[Route('/{slug}/create', name: 'question.create', methods: ['GET', 'POST'])]
    public function store(Survey $survey, Request $request, QuestionRepository $questionRepository): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $question->setSurvey($survey);
            $questionRepository->save($question, true);

            return $this->redirectToRoute('web.question.options', [
                'slug' => $survey->getSlug(),
                'question' => $question->getId(),
            ]);
        }

        return $this->render('question/edit.html.twig', [
            'form' => $form,
            'survey' => $survey,
        ]);
    }

    #[Route('/{slug}/{question}', name: 'question.edit', methods: ['GET', 'PUT'])]
    public function update(
        Survey $survey,
        Question $question,
        Request $request,
        QuestionRepository $questionRepository
    ): Response {
        $form = $this->createForm(QuestionType::class, $question, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question = $form->getData();
            $questionRepository->save($question, true);

            return $this->redirectToRoute('web.question.options', [
                'slug' => $survey->getSlug(),
                'question' => $question->getId(),
            ]);
        }

        return $this->render('question/edit.html.twig', [
            'form' => $form,
            'survey' => $survey,
        ]);
    }

    #[Route('/{slug}/{question}', name: 'question.delete', methods: ['DELETE'])]
    public function destroy(
        Survey $survey,
        Question $question,
        QuestionRepository $questionRepository,
        Request $request
    ): Response {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-question', $submittedToken)) {
            $questionRepository->remove($question, true);
            $this->addFlash('notice', 'Pytanie zostało usunięte.');
        } else {
            $this->addFlash('notice', 'Wystąpił probłem z usuniętitiem.');
        }

        return $this->redirectToRoute('web.survey.questions');
    }
}
