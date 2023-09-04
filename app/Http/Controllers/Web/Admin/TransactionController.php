<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function all()
    {
        $transactions = Transaction::with(['user', 'course'])->orderBy('created_at', 'DESC')->paginate(10);
        $total_pendapatan = Transaction::where('status', 'success')->sum('total_price');
        return view('admin.transaction.all', compact('transactions', 'total_pendapatan'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'course'])->findOrFail($id);
        return view('admin.transaction.show', compact('transaction'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $transactions = Transaction::where(function($query) use ($search) {
            $query->where('code_transaction', 'like', '%' . $search . '%');
        })->paginate(9);
        return view('admin.transaction.all', compact('transactions'));

    }
}
