@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Modul</h1>
        @include('layouts.session')
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>
    <form action="{{route('update.quiz', $question->id)}}" method="post">
        @csrf
        <div class="mb-3 px-5">
            <label for="course_name" class="form-label">Pertanyaan</label>
            <input type="text" class="form-control" id="" name="question" placeholder=""
                   value="{{ $question->question }}">
        </div>
        <div class="mb-3 px-5">
            <label for="module-name">Jawaban Benar = {{$question->correct_answer}}</label>
            <div class="option">
                <div class="radio" >
                    <input type="radio" name="correct_answer" value="a" id="jawaban-a">
                    <label for="jawaban-a">A</label>
                </div>
                <div class="radio">
                    <input type="radio" name="correct_answer" value="b" id="jawaban-b">
                    <label for="jawaban-b">B</label>
                </div>
                <div class="radio">
                    <input type="radio" name="correct_answer" value="c" id="jawaban-c">
                    <label for="jawaban-c">C</label>
                </div>
                <div class="radio">
                    <input type="radio" name="correct_answer" value="d" id="jawaban-d">
                    <label for="jawaban-d">D</label>
                </div>
            </div>
        </div>
        @foreach($answer as $key => $value)
            <div class="mb-3 px-5">
                <label for="course_name" class="form-label">Jawaban {{$value->value}}</label>
                <input type="text" class="form-control" id="" name="answer[]" placeholder=""
                       value="{{ $value->answer }}">
            </div>
        @endforeach
        <div class="card mt-5">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>


    </form>
    <style>
        .btn-add {
            margin-top: 5rem;
            padding: 10px 10rem 10px 10rem;
            border-radius: 10px;
            background-color: orange;
            /* remove outline */
            outline: none;
            color: white;
            /* center */
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            height: 3rem;
            border: 1px solid #ced4da;
        }

        .card {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .custom-file {
            position: relative;
            display: inline-block;
            width: 100%;
            margin-bottom: 0;
        }

        .custom-file-input {
            position: relative;
            z-index: 2;
            width: 100%;
            height: calc(2.25rem + 2px);
            margin: 0;
            opacity: 0;
        }

        .custom-file-label {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1;
            height: calc(2.25rem + 2px);
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            font-weight: 400;
            cursor: pointer;
        }

        .preview {
            text-align: center;
        }

        .preview img {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
        }
    </style>
@endsection
