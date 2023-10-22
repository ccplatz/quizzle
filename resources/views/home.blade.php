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
                                <select class="form-select ms-3" name="number_of_questions" id="questions-select">
                                    <option value="10">10 questions</option>
                                    <option value="20">20 questions</option>
                                    <option value="30">30 questions</option>
                                </select>
                            </form>
                            <ul class="list-group">
                                @foreach ($quizzes as $quiz)
                                    <li class="list-group-item"><span class="me-3">#{{ $loop->index + 1 }}</span>
                                        {{ $quiz->number_of_questions }}
                                        questions - started on {{ $quiz->created_at->format('d.m.Y') }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
