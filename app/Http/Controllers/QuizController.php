<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuizRequest;
use App\Models\Quiz;
use App\Services\QuizService;

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
     */
    public function store(StoreQuizRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $quiz = Quiz::create($validated);

        return redirect()->route('quizzes.next', $quiz);
    }

    public function nextQuestion(Quiz $quiz)
    {
        $question = $this->quizService->getNextQuestion($quiz);

        return view('quizzes.next')->with('question', $question);
    }
}