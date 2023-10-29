@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('quizzes.store-choices', $quiz) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quizPosition" value="{{ $quizPosition->id }}">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Question') }} {{ $quiz->questions->count() }} of {{ $quiz->number_of_questions }}
                        </div>

                        <div class="card-body">
                            <p>{{ $question->text }}</p>
                            @foreach ($question->answers as $answer)
                                <label for="answerCheck-{{ $answer->id }}" class="d-block">
                                    <div class="answer row border rounded my-2 py-1 pointer">
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            {{ $answer->identifier }}</div>
                                        <div class="col-10">{{ $answer->text }}</div>
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <input id="answerCheck-{{ $answer->id }}"
                                                class="answer__check form-check-input mt-0" name="choices[]"
                                                value="{{ $answer->id }}" type="checkbox">
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <input type="submit" value="{{ __('Save and next') }}" class="btn btn-primary float-end my-3">
                </form>
            </div>
        </div>
    </div>
@endsection
