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
        $question = Question::with('answers')->where('id', $id)->first();
        $answer = Answer::where('question_id', $id)->get();
        return view('admin.course.quiz.edit', compact('question','answer'));
    }


        public function update(Request $request, $id)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'correct_answer' => 'required',
            'answer.*' => 'required',
        ]);

        // Cari pertanyaan berdasarkan ID
        $question = Question::find($id);

        if (!$question) {
            return redirect()->back()->with('error', 'Pertanyaan tidak ditemukan.');
        }

        // Update pertanyaan
        try {
            $question->question = $request->input('question');
            $question->correct_answer = $request->input('correct_answer');
            $question->save();

            // Update jawaban
            foreach ($request->input('answer') as $index => $answerText) {
                $answer = Answer::find($index);
                if ($answer) {
                    $answer->answer = $answerText;
                    $answer->save();
                }
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('course.page')->with('success', 'Pertanyaan berhasil diperbarui.');
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
        $questions = Question::with('answers')->where('id', $id)->get();

        $answer = Answer::where('question_id', $id)->get();
        return view('admin.course.quiz.show', compact('questions','answer'));

    }






}
