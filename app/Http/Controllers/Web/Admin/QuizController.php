<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_quiz' => 'required',
            'course_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('warning', $validator->errors());
        }
        try {
            $quiz = Quiz::create([
                'title_quiz' => $request->title_quiz,
                'course_id' => $request->course_id,
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

}
