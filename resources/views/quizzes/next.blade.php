@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Your questions') }}</div>

                    <div class="card-body">
                        {{ dd($question->id) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
