@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('quizzes.next', $quiz) }}" method="POST">
                    <div class="card">
                        <div class="card-header">
                            {{ __('Question') }} {{ $quiz->questions->count() }} of {{ $quiz->number_of_questions }}
                        </div>

                        <div class="card-body">
                            <p>{{ $question->text }}</p>
                            @csrf
                            @foreach ($question->answers as $answer)
                                <div class="answer row border rounded my-2 py-1 pointer">
                                    <div class="col-1 d-flex justify-content-center align-items-center">
                                        {{ $answer->identifier }}</div>
                                    <div class="col-10">{{ $answer->text }}</div>
                                    <div class="col-1 d-flex justify-content-center align-items-center">
                                        <input class="answer__check form-check-input mt-0"
                                            name="answers[{{ $answer->id }}]" type="checkbox"
                                            @checked(old('answers.' . $answer->id))>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <input type="submit" value="{{ __('Save and next') }}" class="btn btn-primary float-end my-3">
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/quizzes/next.js'])
@endpush
