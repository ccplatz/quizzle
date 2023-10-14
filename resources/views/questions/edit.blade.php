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
                                <textarea class="form-control" name="text" id="text" rows="5">{{ $old->text ?? $question->text }}</textarea>
                            </div>
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
