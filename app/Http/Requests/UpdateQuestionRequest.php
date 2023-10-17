<?php

namespace App\Http\Requests;

use App\Models\Question;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->question);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $identifiersArray = range(Question::FIRST_ANSWER, Question::LAST_ANSWER);
        $identifiersString = Arr::join($identifiersArray, ',');

        return [
            'text' => 'required|min:50|max:1000',
            'answers' => [
                'required',
                'array:' . $identifiersString,
                // Check if at least one answer has text
                function (string $attribute, mixed $value, Closure $fail) {
                    $allValuesAreEmpty = true;
                    foreach ($value as $answer) {
                        if (!empty($answer['text'])) {
                            $allValuesAreEmpty = false;
                            break;
                        }
                    }
                    if ($allValuesAreEmpty) {
                        $fail("There has to be at least one answer.");
                    }
                },
                // Check if at least one answer is set correct
                function (string $attribute, mixed $value, Closure $fail) {
                    $noCorrectAnswer = true;
                    foreach ($value as $answer) {
                        if (!empty($answer['correct'])) {
                            $noCorrectAnswer = false;
                            break;
                        }
                    }
                    if ($noCorrectAnswer) {
                        $fail("There has to be at least one answer that is correct.");
                    }
                },
            ],
            'answers.A.text' => 'required_with:answers.A.correct',
            'answers.B.text' => 'required_with:answers.B.correct',
            'answers.C.text' => 'required_with:answers.C.correct',
            'answers.D.text' => 'required_with:answers.D.correct',
            'answers.E.text' => 'required_with:answers.E.correct',
            'answers.F.text' => 'required_with:answers.F.correct',
            'answers.G.text' => 'required_with:answers.G.correct',
            'answers.H.text' => 'required_with:answers.H.correct',
            'answers.I.text' => 'required_with:answers.I.correct',
            'answers.J.text' => 'required_with:answers.J.correct',
        ];
    }
}