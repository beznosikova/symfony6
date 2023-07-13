<?php

namespace App\Controller\Web;

use App\Entity\Survey;
use App\Form\SurveyType;
use App\Repository\QuestionRepository;
use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SurveyController extends AbstractController
{
    #[Route('/', name: 'survey.index', methods: ['GET', 'HEAD'])]
    public function index(SurveyRepository $surveyRepository): Response
    {
        return $this->render('survey/index.html.twig', [
            'surveys' => $surveyRepository->findAll(),
        ]);
    }

    #[Route('/survey/create', name: 'survey.create', methods: ['GET', 'POST'])]
    public function store(Request $request, SurveyRepository $surveyRepository): RedirectResponse|Response
    {
        $survey = new Survey();
        $form = $this->createForm(SurveyType::class, $survey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();
            $surveyRepository->save($survey, true);
            // todo: add created_at and updated_at

            return $this->redirectToRoute('web.survey.questions', ['survey' => $survey->getId()]);
        }

        return $this->render('survey/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/survey/edit/{survey}', name: 'survey.edit', methods: ['GET', 'HEAD'])]
    public function edit(Survey $survey)//: View
    {
        dd('survey.edit', $survey);
//        $statuses = SurveyStatus::cases();
//
//        return view('survey.forms.edit', compact('survey', 'statuses'));
    }

    #[Route('/survey/edit/{survey}', name: 'survey.update', methods: ['PUT'])]
//    public function update(Survey $survey, SurveyUpdateRequest $request): RedirectResponse
    public function update(
        Survey $survey
    ): RedirectResponse {
        dd('survey.update', $survey);
//        $validated = $request->validated();
//
//        if ($survey->questions->isEmpty() && $request->status === SurveyStatus::READY->value) {
//            return back()->withInput()->withErrors(
//                ['status' => 'Statusu “Gotowe” nie można ustawić, badanie nie zawiera pytań']
//            );
//        }
//
//        $survey->fill($validated);
//        $survey->save();
//
//        return redirect(route('survey.questions', compact('survey')));
    }

    #[Route('/survey/{survey}', name: 'survey.delete', methods: ['DELETE'])]
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

    #[Route('/survey/show/{survey}', name: 'survey.show', methods: ['GET', 'HEAD'])]
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