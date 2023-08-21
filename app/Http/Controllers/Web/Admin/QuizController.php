<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'correct_answer' => 'required',
            'a' => 'required',
            'b' => 'required',
            'c' => 'required',
            'd' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('warning', $validator->errors());
        }
        try {
            $question = Question::create([
                'question' => $request->question,
                'correct_answer' => $request->correct_answer,
                'quiz_id' => $request->quiz_id,
            ]);
            Answer::create([
                'answer' => $request->a,
                'value' => 'a',
                'question_id' => $question->id,
            ]);
            Answer::create([
                'answer' => $request->b,
                'value' => 'b',
                'question_id' => $question->id,
            ]);
            Answer::create([
                'answer' => $request->c,
                'value' => 'c',
                'question_id' => $question->id,
            ]);
            Answer::create([
                'answer' => $request->d,
                'value' => 'd',
                'question_id' => $question->id,
            ]);

            return redirect()->back()->with('success', 'Berhasil menambahkan quiz');
        } catch (\Throwable $th) {
            return redirect()->back()->with('status', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $quiz = Quiz::find($id);
        return view('admin.course.quiz.edit', compact('quiz'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'soal' => 'required',
            'jawaban' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('warning', $validator->errors());
        }
        try {
            $quiz = Quiz::find($id);
            $quiz->update([
                'soal' => $request->soal,
                'jawaban' => $request->jawaban,
            ]);
            return redirect()->route('course.show', $quiz->course_id)->with('warning', 'Berhasil mengubah quiz');
        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', $th->getMessage());
        }
        # code...
    }

    public function delete($id)
    {
        $question = Question::find($id);
        if ($question){
            try {
                DB::beginTransaction();
                Answer::where('question_id', $question->id)->delete();
                $question->delete();
                DB::commit();
                return redirect()->back()->with('success', 'Berhasil menghapus quiz');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }

    public function show($id)
    {
        $question = Question::with('answers')->where('quiz_id', $id)->get();
        $answer = Answer::where('question_id', $question->id)->get();
        return view('admin.course.quiz.show', compact('question','answer'));

    }




}
