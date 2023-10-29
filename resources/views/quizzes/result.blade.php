@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Your results') }}</div>

                    <div class="card-body">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th scope="col">Question</th>
                                    <th scope="col">Correct answers</th>
                                    <th scope="col">Your choices</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-success">
                                    <th scope="row">#1</th>
                                    <td>A B C</td>
                                    <td>B <span class="text-success">B</span> <span class="text-danger">B</span></td>
                                </tr>
                                <tr class="table-danger">
                                    <th scope="row">#1</th>
                                    <td>A <span class="text-danger">B</span> <span class="text-success">C</span></td>
                                    <td>B C D</td>
                                </tr>
                            </tbody>
                        </table>
                        @foreach ($questions as $question)
                            Question #{{ $loop->index + 1 }} <br>
                            id: {{ $question->id }} <br>
                            {{ $question->text }} <br>
                            {{ $question->quizPosition->isCorrect() ? 'richtig' : 'falsch' }} <br>
                            choices:
                            @foreach ($question->quizPosition->answers as $choice)
                                id:{{ $choice->id }} <br>
                                correct: {{ $choice->correct }} <br>
                            @endforeach
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
