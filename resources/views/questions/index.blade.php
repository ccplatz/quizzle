@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Your questions') }}</div>

                    <div class="card-body">
                        @if (count($questions) < 1)
                            {{ __("You don't have any questions yet!") }} <i class="bi bi-emoji-frown"></i>
                        @else
                            <div class="list-group mb-2">
                                @foreach ($questions as $question)
                                    <a href="{{ route('questions.edit', $question) }}"
                                        class="list-group-item list-group-item-action">
                                        <div>
                                            {{ __('Question') }} #{{ $loop->index + $questions->firstItem() }}
                                        </div>
                                        <div>{{ Str::limit($question->text, 50) }}</div>
                                    </a>
                                @endforeach
                            </div>
                            {{ $questions->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
