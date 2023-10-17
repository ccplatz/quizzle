@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit question') }}</div>

                    <div class="card-body">
                        <form action="{{ route('questions.update', $question) }}" method="POST">
                            @method('PATCH')
                            @csrf()
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-question-lg"></i></span>
                                <textarea class="form-control" name="text" id="text" rows="5">{{ old('text') ?? $question->text }}</textarea>
                            </div>
                            @foreach (range($firstAnswerIdentifier, $lastAnswerIdentifier) as $char)
                                <div class="input-group mb-3">
                                    <input type="hidden" name="answers[{{ $char }}][id]"
                                        value="@if ($question->answers->get($loop->index)) {{ $question->answers->get($loop->index)->id }} @endif">
                                    <span class="input-group-text">{{ $char }}</span>
                                    <input type="text" class="form-control" name="answers[{{ $char }}][text]"
                                        value="@if (old('answers.' . $char . '.text')) {{ old('answers.' . $char . '.text') }} 
                                        @elseif ($question->answers->get($loop->index)) {{ $question->answers->get($loop->index)->text }} @endif" />
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" name="answers[{{ $char }}][correct]"
                                            type="checkbox"
                                            @if (old('answers.' . $char . '.correct')) @checked(old('answers.' . $char . '.correct'))
                                            @elseif ($question->answers->get($loop->index))
                                            @checked(($question->answers->get($loop->index))->correct) @endif>
                                    </div>
                                </div>
                            @endforeach
                            <a href="{{ route('questions.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            <a href="{{ route('questions.destroy', $question) }}" class="btn btn-danger"
                                onclick="event.preventDefault();
                                document.getElementById('delete-form').submit();">
                                {{ __('Delete') }}
                            </a>
                            <input type="submit" value="{{ __('Save') }}" class="btn btn-primary float-end">
                        </form>
                        <form id="delete-form" action="{{ route('questions.destroy', $question) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
