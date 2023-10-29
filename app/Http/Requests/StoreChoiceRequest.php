<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StoreChoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'choices' => 'required|array',
            'quizPosition' => 'required|numeric|exists:App\Models\QuizPosition,id'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'choices.required' => 'There has to be at least one answer chosen as correct.'
        ];
    }
}
