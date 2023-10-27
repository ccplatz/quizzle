@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Question') }} {{ $quiz->questions->count() }} of {{ $quiz->number_of_questions }}
                    </div>

                    <div class="card-body">
                        <p>{{ $question->text }}</p>
                        <form action="{{ route('quizzes.next', $quiz) }}" method="POST">
                            @csrf
                            @foreach ($question->answers as $answer)
                                <div class="row border rounded my-2 py-1">
                                    <div class="col-1">{{ $answer->identifier }}</div>
                                    <div class="col-10">{{ $answer->text }}</div>
                                    <div class="col-1">
                                        <input class="form-check-input mt-0" name="answers[{{ $answer->id }}]"
                                            type="checkbox" @checked(old('answers.' . $answer->id))>
                                    </div>
                                </div>
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
