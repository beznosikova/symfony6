<?php

namespace App\Controller\Web;

use App\Entity\Survey;
use App\Form\SurveyType;
use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SurveyController extends AbstractController
{
    #[Route('/', name: 'survey.index', methods: ['GET', 'HEAD'])]
    public function index(SurveyRepository $surveyRepository): Response
    {
        $surveys = $surveyRepository->findAll();

        return $this->render('survey/index.html.twig', compact('surveys'));
    }

    #[Route('/survey/create', name: 'survey.create', methods: ['GET', 'HEAD'])]
    public function create(): Response
    {
        $form = $this->createForm(SurveyType::class, new Survey());

        return $this->render('survey/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/survey/create', name: 'survey.store', methods: ['POST'])]
    public function store(Request $request, SurveyRepository $surveyRepository): RedirectResponse
    {
        $survey = new Survey();
        $form = $this->createForm(SurveyType::class, $survey);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $survey = $form->getData();
            $surveyRepository->save($survey, true);

            return $this->redirectToRoute('web.survey.questions', ['survey' => $survey->getId()]);
        }
        dd('Form is not valid', $request);
        //        TODO: validation
    }

    #[Route('/survey/edit/{survey}', name: 'survey.edit', methods: ['GET', 'HEAD'])]
    public function edit(Survey $survey)//: View
    {
        dd('survey.edit', $survey);
//        $statuses = SurveyStatus::cases();
//
//        return view('survey.forms.edit', compact('survey', 'statuses'));
    }

    #[Route('/survey/edit/{survey}', name: 'survey.edit', methods: ['PUT'])]
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
    public function destroy(Survey $survey, SurveyRepository $surveyRepository): RedirectResponse
    {
        $surveyRepository->remove($survey, true);
        $this->addFlash(
            'notice',
            'Badanie zostało usunięte.'
        );
        return $this->redirectToRoute('web.survey.index');
    }

    #[Route('/survey/show/{survey}', name: 'survey.show', methods: ['GET', 'HEAD'])]
    public function show(Survey $survey)//: View
    {
        dd('Survey.show', $survey);
//        if ($survey->in_edition) {
//            abort(404);
//        }
//
//        return view('survey.show', compact('survey'));
    }
}