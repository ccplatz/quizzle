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
                                @if ($question->answers->where('identifier', $char)->count() > 0)
                                    @php
                                        $answer = $question->answers->where('identifier', $char)->first();
                                    @endphp
                                    <input type="hidden" name="answers[{{ $char }}][id]"
                                        value="{{ $answer->id }}">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">{{ $answer->identifier }}</span>
                                        <input type="text" class="form-control"
                                            name="answers[{{ $answer->identifier }}][text]"
                                            value="@if (old('answers.' . $answer->identifier . '.text')) {{ old('answers.' . $answer->identifier . '.text') }} 
                                        @else {{ $answer->text }} @endif" />
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0"
                                                name="answers[{{ $answer->identifier }}][correct]" type="checkbox"
                                                @if (old('answers.' . $answer->identifier . '.correct')) @checked(old('answers.' . $answer->identifier . '.correct'))
                                            @else @checked($answer->correct) @endif>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="answers[{{ $char }}][indentifier]"
                                        value="{{ $char }}">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">{{ $char }}</span>
                                        <input type="text" class="form-control"
                                            name="answers[{{ $char }}][text]"
                                            value="{{ old('answers.' . $char . '.text') }}" />
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0"
                                                name="answers[{{ $char }}][correct]" type="checkbox"
                                                @checked(old('answers.' . $char . '.correct'))>
                                        </div>
                                    </div>
                                @endif
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
