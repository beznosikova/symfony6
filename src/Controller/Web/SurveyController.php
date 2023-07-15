<?php

namespace App\Controller\Web;

use App\Entity\Survey;
use App\Form\SurveyEditType;
use App\Form\SurveyType;
use App\Repository\QuestionRepository;
use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SurveyController extends AbstractController
{
    #[Route('/', name: 'survey.index', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        return $this->render('survey/index.html.twig');
    }

    #[Route('/survey/create', name: 'survey.create', methods: ['GET', 'POST'])]
    public function store(Request $request, SurveyRepository $surveyRepository): Response
    {
        $survey = new Survey();
        $form = $this->createForm(SurveyType::class, $survey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();
            $surveyRepository->save($survey, true);

            return $this->redirectToRoute('web.survey.questions', ['survey' => $survey->getSlug()]);
        }

        return $this->render('survey/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/survey/edit/{slug}', name: 'survey.edit', methods: ['GET', 'PUT'])]
    public function update(Request $request, Survey $survey, SurveyRepository $surveyRepository): Response
    {
        $form = $this->createForm(SurveyEditType::class, $survey, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();
            $surveyRepository->save($survey, true);

            return $this->redirectToRoute('web.survey.questions', ['slug' => $survey->getSlug()]);
        }

        return $this->render('survey/edit.html.twig', ['form' => $form,]);
    }

    #[Route('/survey/{slug}', name: 'survey.delete', methods: ['DELETE'])]
    public function destroy(Survey $survey, SurveyRepository $surveyRepository, Request $request): RedirectResponse
    {
        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-survey', $submittedToken)) {
            $surveyRepository->remove($survey, true);
            $this->addFlash('notice', 'Badanie zostało usunięte.');
        } else {
            $this->addFlash('notice', 'Wystąpił probłem z usuniętitiem.');
        }

        return $this->redirectToRoute('web.survey.index');
    }

    #[Route('/survey/show/{slug}', name: 'survey.show', methods: ['GET', 'HEAD'])]
    public function show(Survey $survey, Request $request, QuestionRepository $questionRepository): Response
    {
        if ($survey->inEdition()) {
            throw new NotFoundHttpException('Sorry survey is in edition!');
        }

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $questionRepository->getCommentPaginator($survey, $offset);

        return $this->render('survey/show.html.twig', [
            'survey' => $survey,
            'questions' => $paginator,
            'previous' => $offset - QuestionRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + QuestionRepository::PAGINATOR_PER_PAGE),
        ]);
    }
}