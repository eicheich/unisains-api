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
            'soal' => 'required',
            'jawaban' => 'required',
            'course_id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('status', $validator->errors());
        }
        try {
            $quiz = Quiz::create([
                'soal' => $request->soal,
                'jawaban' => $request->jawaban,
                'course_id' => $request->course_id,
            ]);
            return redirect()->back()->with('status', 'Berhasil menambahkan quiz');
        } catch (\Throwable $th) {
            return redirect()->back()->with('status', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $quiz = Quiz::find($id);
        return view('admin.course.quiz.edit', compact('quiz'));
    }
    
}
