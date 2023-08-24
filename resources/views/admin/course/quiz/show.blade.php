@extends('layouts.main')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Soal</h1>
        @include('layouts.session')
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Question Details
                </div>
                <div class="card-body">
                    <h5 class="card-title">Question Title: {{ $questions->question }}</h5>
{{--                    <p class="card-text">Question Content: {{ $question->content }}</p>--}}
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    Answers
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($questions->answers as $answer)
                        <li class="list-group-item">
                            Answer: {{ $answer->content }}
                            @if ($answer->is_correct)
                                <span class="badge badge-success ml-2">Correct</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
