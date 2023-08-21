<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function all()
    {
        $transactions = Transaction::with(['user', 'course'])->paginate(10);
        return view('admin.transaction.all', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'course'])->findOrFail($id);
        return view('admin.transaction.show', compact('transaction'));
    }
}
