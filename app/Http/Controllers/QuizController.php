<?php

namespace App\Http\Controllers;

use App\Exceptions\NoQuestionAvailableException;
use App\Http\Requests\StoreQuizRequest;
use App\Models\Quiz;
use App\Services\QuizService;
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
    public function nextQuestion(Quiz $quiz)
    {
        if ($quiz->isFinished()) {
            return redirect()->route('quizzes.result', $quiz);
        }

        try {
            $question = $this->quizService->getNextQuestion($quiz);
        } catch (NoQuestionAvailableException $e) {
            Log::error($e->getMessage());
            return redirect()->route('home')->withErrors($e->getMessage());
        }
        $quiz->questions()->attach($question->id);
        $quiz->refresh();

        return view('quizzes.next')->with(
            [
                'quiz' => $quiz,
                'question' => $question
            ]
        );
    }

    /**
     * Show the result to the user.
     *
     * @param  mixed $quiz
     * @return void
     */
    public function result(Quiz $quiz)
    {
        return view('quizzes.result')->with(
            [
                'quiz' => $quiz
            ]
        );
    }
}