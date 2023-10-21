@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create question') }}</div>

                    <div class="card-body">
                        <form action="{{ route('questions.store') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-question-lg"></i></span>
                                <textarea class="form-control" name="text" id="text" rows="5"
                                    placeholder="{{ __('Type your question here and your answers below. Set correct answers with checkboxes. Empty answers will be ignored.') }}">{{ old('text') }}</textarea>
                            </div>
                            @foreach (range($firstAnswerIdentifier, $lastAnswerIdentifier) as $char)
                                <input type="hidden" name="answers[{{ $char }}][identifier]"
                                    value="{{ $char }}">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">{{ $char }}</span>
                                    <input type="text" class="form-control" name="answers[{{ $char }}][text]"
                                        value="{{ old('answers.' . $char . '.text') }}" />
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0" name="answers[{{ $char }}][correct]"
                                            type="checkbox" @checked(old('answers.' . $char . '.correct'))>
                                    </div>
                                </div>
                            @endforeach
                            <a href="{{ route('questions.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            <input type="submit" value="{{ __('Save') }}" class="btn btn-primary float-end">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
