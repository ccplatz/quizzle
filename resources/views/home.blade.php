@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Your quizzes') }}</div>

                    <div class="card-body">
                        @if (count($quizzes) < 1)
                            {{ __("You don't have any quizzes yet!") }} <i class="bi bi-emoji-frown"></i>
                        @else
                            <form action="{{ route('quizzes.store') }}" method="post" class="d-flex pb-3">
                                @csrf
                                <input type="submit" class="btn btn-primary" value="{{ __('Start new Quiz with') }}">
                                <select class="form-select ms-2" name="number_of_questions" id="questions-select">
                                    <option value="10">10 questions</option>
                                    <option value="20">20 questions</option>
                                    <option value="30">30 questions</option>
                                </select>
                            </form>
                            <div class="list-group">
                                @foreach ($quizzes as $quiz)
                                    <a class="list-group-item list-group-item-action"
                                        href="{{ route('quizzes.next', $quiz) }}">
                                        <div class="row">
                                            <div class="col-2">
                                                <span class="me-2">#{{ $loop->index + 1 }}</span>
                                            </div>
                                            <div class="col-8">
                                                @if ($quiz->isFinished())
                                                    {{ $quiz->getCountOfCorrectQuizPositions() }}/{{ $quiz->number_of_questions }}
                                                    correct
                                                @else
                                                    {{ $quiz->number_of_questions }}
                                                    questions
                                                @endif
                                                - {{ $quiz->created_at->format('d.m.Y') }}
                                            </div>
                                            <div class="col-2 d-flex justify-content-center align-items-center">
                                                @if ($quiz->isFinished())
                                                    <span class="text-success float-end"><i class="bi bi-check"></i></span>
                                                @else
                                                    <span class="badge bg-primary rounded-pill float-end">
                                                        {{ $quiz->getCountOfOpenQuizPositions() }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
