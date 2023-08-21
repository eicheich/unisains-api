<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Helpers\CertificateGenerator;
use App\Helpers\StatusHelper;
use App\Http\Controllers\Controller;
use App\Mail\MailNotify;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\MyCourse;
use App\Models\Transaction;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
//php mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $carbonckeck = Carbon::now();
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $course = DB::table('courses')->where('id', $request->course_id)->first();
        if ($course == null) {
            return response()->json([
                'message' => 'Course not found',
            ], 404);
        }
        $my_course = DB::table('my_courses')->where('user_id', $user->id)->where('course_id', $request->course_id)->first();
        if ($my_course) {
            return response()->json([
                'message' => 'You already have this course',
            ], 200);
        }

        if ($course->is_paid == 0) {
            try {
                DB::beginTransaction();
                $transaction = DB::table('transactions')->insert([
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                    'status' => 'pending',
                    'code_transaction' => 'TRU' . time(),
                    'total_price' => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                DB::table('carts')->where('user_id', $user->id)->where('course_id', $request->course_id)->delete();
                DB::commit();
                activity()->causedBy($transaction)->log('Added Transaction '. $user->email);
                return response()->json([
                    'message' => 'Transaction added',
                ], 201);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status', $th->getMessage()
                ], 500);
            }
        } elseif ($course->is_paid == 1) {
            try {
                DB::beginTransaction();

                $code_transaction = 'TRU' . time();
                $transaction = [
                    'user_id' => $user->id,
                    'course_id' => $request->course_id,
                    'status' => 'pending',
                    'code_transaction' => $code_transaction,
                    'total_price' => $course->price,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                DB::table('transactions')->insert($transaction);
                $newTransaction = DB::table('transactions')->where('code_transaction', $code_transaction)->first();
                DB::commit();
                $trx = Transaction::where('code_transaction', $code_transaction)->first();
                activity()->causedBy($trx)->log('Added Transaction '. $user->email);
                return response()->json([
                    'message' => 'transaction added',
                    'data' => [
                        'transaction' => $newTransaction
                    ]
                ], 201);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'message', $th->getMessage()
                ], 500);
            }
        }
    }

    public function schedule($transactionId)
    {
        $transaction = DB::table('transactions')->where('id', $transactionId)->first();

        if ($transaction->status == 'pending') {
            // Calculate due time as 1 day after the transaction's creation time
            $dueTime = Carbon::parse($transaction->created_at)->addDay();
            // Schedule the task to run at the due time
            Cache::put('transaction_due_' . $transaction->id, true, $dueTime);
            // Register the task
            Cache::put('transaction_due_task_' . $transaction->id, function () use ($transactionId) {
                $this->updateTransactionStatus($transactionId);
            }, $dueTime);
        }
    }

    public function all()
    {
        $user = Auth::user();
//        panggil function statusUpdate dari StatusHelper
        $statusHelper = new StatusHelper();
        $statusHelper->statusUpdate();
        $transactions = Transaction::with('course')->where('user_id', $user->id)->get();

        if ($transactions->count() == 0) {
            return response()->json([
                'message' => 'transactions not found',
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => [
                'transactions' => $transactions,
            ],
        ], 200);


//        $transactions = DB::table('transactions')
//            ->join('courses', 'transactions.course_id', '=', 'courses.id')
//            ->select('transactions.*', 'courses.title_course', 'courses.image_course', 'courses.price', 'courses.is_paid', 'courses.description')
//            ->where('transactions.user_id', $user->id)
//            ->get();

//        $currentTime = Carbon::now();
//
//        // Calculate the remaining time for each pending transaction
//        foreach ($transactions as $transaction) {
//            if ($transaction->status == 'pending') {
//                $createdAt = Carbon::parse($transaction->created_at);
//                $dueTime = $createdAt->addDay();
//
//                if ($dueTime > $currentTime) {
//                    $remainingTime = $dueTime->diffForHumans($currentTime, [
//                        'syntax' => CarbonInterface::DIFF_ABSOLUTE,
//                        'parts' => 2,
//                    ]);
//                } else {
//                    $remainingTime = 'Expired';
//                    DB::table('transactions')->where('id', $transaction->id)->update([
//                        'status' => 'failed',
//                        'updated_at' => Carbon::now(),
//                    ]);
//                }
//                $transaction->remaining_time = $remainingTime;
//            }
//        }

    }

    public function show($id)
    {
        $user = Auth::user();
        $statusHelper = new StatusHelper();
        $statusHelper->statusUpdate();
        $transaction = Transaction::with('course')->where('id', $id)->where('user_id', $user->id)->first();

        if ($transaction == null) {
            return response()->json([
                'message' => 'transaction not found',
            ], 404);
        }

        return response()->json([
            'message' => 'success',
            'data' => [
                'transaction' => $transaction,
            ],
        ], 200);
    }

    public function quiz(Request $request, $id)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|array',
            'question_id.*' => 'required',
            'answer' => 'required|array',
            'answer.*' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        $id = $user->id; // Assuming you need the user's ID to fetch the course
        $course = DB::table('my_courses')
            ->join('courses', 'my_courses.course_id', '=', 'courses.id')
            ->select('courses.*')
            ->where('my_courses.user_id', $id) // Assuming you want to match the user_id, change 'id' to 'user_id' if needed
            ->first();

        if (!$course) {
            return response()->json([
                'message' => 'Course not found',
            ], 404);
        }
        $questions = DB::table('questions')
            ->whereIn('id', $request->question_id)
            ->get();

        $correct_answers = [];
        foreach ($questions as $question) {
            $correct_answers[$question->id] = $question->correct_answer;
        }
        $given_answers = $request->answer;
        $correct_count = 0;
        foreach ($given_answers as $key => $given_answer) {
            $question_id = $request->question_id[$key];
            if (isset($correct_answers[$question_id]) && $given_answer === $correct_answers[$question_id]) {
                $correct_count++;
            }
        }
        $total_questions = count($questions);
        $score_percentage = $correct_count * 20;
        if ($score_percentage >= 80) {
            try {
                DB::beginTransaction();
                DB::table('my_courses')->where('id', $id)->update([
                    'is_done' => "1",
                ]);
                $time = $user->id.$course->id;
                DB::table('certificates')->insert([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'path' => 'certificate/' . $time . '.pdf',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                DB::commit();
                CertificateGenerator::generate($user->first_name .' '. $user->last_name, $course->title_course, Carbon::now()->format('d F Y'),$score_percentage, $time);
                $certificate = Certificate::where('user_id', $user->id)->where('course_id', $course->id)->first();
                $data = [
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'score' => $score_percentage,
                    'date' => Carbon::now()->format('d F Y'),
                    'course' => $course->title_course,
                    'link_certificate' => 'admin.unisains.com/storage/images/'.$certificate->path,
                    'time' => $time
                ];
                Mail::to($user->email)->send(new MailNotify($data));
                $modelCourse = Course::find($course->id);
                activity()->causedBy($user)->log('Completed the course ' . $user->email);
                $response = [
                    'message' => 'success',
                    'data' => [
                        'user_score' => $score_percentage,
                    ],
                ];
                return response()->json($response, 200);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'message' => 'An error occurred while updating the course status',
                    'error' => $th->getMessage(),
                ], 500);
            }
        } else {
            return response()->json(['message' => $score_percentage]);
        }


    }








}
