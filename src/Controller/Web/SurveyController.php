<?php

namespace App\Controller\Web;

use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SurveyController extends AbstractController
{
    #[Route('/', name: 'surveys.index', methods: ['GET', 'HEAD'])]
    public function index(SurveyRepository $surveyRepository): Response
    {
        $surveys = $surveyRepository->findAll();

        return $this->render('surveys/index.html.twig', compact('surveys'));
    }

//Route::get('/create', [SurveyController::class, 'create'])->name('surveys.create');
//Route::post('/create', [SurveyController::class, 'store']);
//survey
    #[Route('/survey/create', name: 'surveys.create', methods: ['GET', 'HEAD'])]
    public function create(): Response
    {
        dd('surveys.forms.create');
    }

    #[Route('/survey/create/post', name: 'surveys.store', methods: ['GET'])]
//    public function store(SurveyCreateRequest $request): RedirectResponse
    public function store(): RedirectResponse
    {
        dd('surveys.store');
//        $validated = $request->validated();
//        $survey = Survey::create($validated);
//
//        return redirect(route('surveys.questions', compact('survey')));
    }

//    public function edit(Survey $survey): View
//    {
//        $statuses = SurveyStatus::cases();
//
//        return view('surveys.forms.edit', compact('survey', 'statuses'));
//    }
//
//    public function update(Survey $survey, SurveyUpdateRequest $request): RedirectResponse
//    {
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
//        return redirect(route('surveys.questions', compact('survey')));
//    }
//
//    public function destroy(Survey $survey): RedirectResponse
//    {
//        $survey->delete();
//
//        return back()->with('status', 'Badanie zostało usunięte.');
//    }
//
//    public function show(Survey $survey): View
//    {
//        if ($survey->in_edition) {
//            abort(404);
//        }
//
//        return view('surveys.show', compact('survey'));
//    }
//
//    public function test(Survey $survey, Request $request): RedirectResponse
//    {
//        /* TODO: check result */
//
//        return redirect(route('surveys.index'));
//    }
}