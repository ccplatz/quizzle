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
                            <ul class="list-group">
                                @foreach ($quizzes as $quiz)
                                    <li class="list-group-item">#{{ $loop->index + 1 }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
