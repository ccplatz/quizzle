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
                                @foreach ($result as $key => $item)
                                    <tr
                                        class="table-{{ $item['question']->quizPosition->isCorrect() ? 'success' : 'danger' }}">
                                        <th scope="row">#{{ $key }}</th>
                                        @if ($item['question']->trashed())
                                            <td colspan="2">{{ __('Question was deleted.') }}</td>
                                        @else
                                            <td>
                                                @foreach ($item['correctAnswers'] as $answer)
                                                    <span
                                                        class="text-{{ $item['choices']->where('identifier', $answer->identifier)->count() > 0 ? 'success' : 'danger' }}">
                                                        {{ $answer->identifier }}&nbsp;</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($item['choices'] as $answer)
                                                    <span class="text-{{ $answer->correct ? 'success' : 'danger' }}">
                                                        {{ $answer->identifier }}&nbsp;</span>
                                                @endforeach
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
