<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class ReportController extends Controller
{
    function store (Request $request) {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'report' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();
            $report = DB::table('reports')->insert([
                'user_id' => $user->id,
                'report' => $request->report,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Report added',
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status', $th->getMessage()
            ], 500);
        }
        
    }
}
