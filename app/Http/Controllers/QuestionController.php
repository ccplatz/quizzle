<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Answer;
use App\Models\Question;
use App\Services\CreateAnswersService;
use App\Services\UpdateAnswersService;

class QuestionController extends Controller
{
    /**
     * @var CreateAnswersService $createAnswersService
     */
    private CreateAnswersService $createAnswerService;

    /**
     * @var UpdateAnswersService $updateAnswersService
     */
    private UpdateAnswersService $updateAnswersService;

    public function __construct(CreateAnswersService $createAnswersService, UpdateAnswersService $updateAnswersService)
    {
        $this->createAnswerService = $createAnswersService;
        $this->updateAnswersService = $updateAnswersService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::where('user_id', auth()->user()->id)->paginate(10);
        return view('questions.index')->with(
            [
                'questions' => $questions
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('questions.create')->with(
            [
                'firstAnswerIdentifier' => Question::FIRST_ANSWER,
                'lastAnswerIdentifier' => Question::LAST_ANSWER
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request)
    {
        $validatedQuestionData = $request->safe()->only(['text']);
        $validatedQuestionData['user_id'] = auth()->user()->id;
        $question = Question::create($validatedQuestionData);

        $validatedAnswersData = $request->safe()->except(['text']);
        $this->createAnswerService->createAnswersFromRequestInput($validatedAnswersData, $question);

        return redirect()->route('questions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        if (request()->user()->cannot('view', $question)) {
            abort(403);
        }

        return view('questions.edit')->with(
            [
                'question' => $question,
                'firstAnswerIdentifier' => Question::FIRST_ANSWER,
                'lastAnswerIdentifier' => Question::LAST_ANSWER
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $validatedQuestionData = $request->safe()->only(['text']);
        $question->update($validatedQuestionData);

        $validatedAnswersData = $request->safe()->except(['text']);
        $this->updateAnswersService->updateAnswersFromRequestInput($validatedAnswersData, $question);

        return redirect()->route('questions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        if (request()->user()->cannot('delete', $question)) {
            abort(403);
        }

        $question->delete();

        return redirect()->route('questions.index');
    }
}