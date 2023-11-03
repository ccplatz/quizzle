<?php

namespace App\Http\Controllers;

use App\Exceptions\NoQuestionAvailableException;
use App\Http\Requests\StoreChoiceRequest;
use App\Http\Requests\StoreQuizRequest;
use App\Models\Quiz;
use App\Models\QuizPosition;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    /**
     * @var QuizService $quizService
     */
    protected QuizService $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  mixed $request
     * @return void
     */
    public function store(StoreQuizRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $quiz = Quiz::create($validated);

        return redirect()->route('quizzes.next', $quiz);
    }

    /**
     * Show the next question to the user.
     *
     * @param  mixed $quiz
     * @return void|\Illuminate\Http\RedirectResponse
     */
    public function nextQuestion(Quiz $quiz, Request $request)
    {
        if ($request->user()->cannot('view', $quiz)) {
            abort(403);
        }

        if ($quiz->isFinished()) {
            return redirect()->route('quizzes.result', $quiz);
        }

        try {
            $question = $this->quizService->getNextQuestion($quiz);
        } catch (NoQuestionAvailableException $e) {
            Log::error($e->getMessage());
            return redirect()->route('home')->withErrors($e->getMessage());
        }
        $this->quizService->addNewQuestion($quiz, $question);
        $quiz->refresh();
        $quizPosition = $quiz->questions->where('id', $question->id)->first()->quizPosition;

        return view('quizzes.next')->with(
            [
                'quiz' => $quiz,
                'question' => $question,
                'quizPosition' => $quizPosition,
            ]
        );
    }

    /**
     * Show the result to the user.
     *
     * @param  mixed $quiz
     * @return void
     */
    public function result(Quiz $quiz, Request $request)
    {
        if ($request->user()->cannot('view', $quiz)) {
            abort(403);
        }

        $result = $this->quizService->getResult($quiz);

        return view('quizzes.result')->with(
            [
                'quiz' => $quiz,
                'questions' => $quiz->questions,
                'result' => $result
            ]
        );
    }

    /**
     * Store the choices for the last question.
     *
     * @param  mixed $request
     * @param  mixed $quiz
     * @return void
     */
    public function storeChoices(StoreChoiceRequest $request, Quiz $quiz)
    {
        if ($request->user()->cannot('view', $quiz)) {
            abort(403);
        }

        $validated = $request->validated();
        $quizPosition = QuizPosition::findOrfail($validated['quizPosition']);

        foreach ($validated['choices'] as $choice) {
            $quizPosition->answers()->attach($choice);
        }

        return redirect()->route('quizzes.next', $quiz);
    }
}