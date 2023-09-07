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
        $total_pendapatan = Transaction::where('status', 'success')->sum('total_price');
        $query = Transaction::query()
            ->with(['user', 'course'])
            ->orderBy('created_at', 'DESC');
        $searchTerm = $request->search;
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('code', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('email', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        } else {
            $query->where('status', 'success');
        }
        $filterStatus = $request->filter_status;
        if ($filterStatus) {
            $query->where('status', $filterStatus);
        } else {
            $query->where('status', 'success');
        }
        $filterDate = $request->filter_date;
        if ($filterDate) {
            $query->whereDate('created_at', $filterDate);
        } else {
            $query->where('status', 'success');
        }
        $transactions = $query->paginate(10);

        return view('admin.transaction.all', compact('transactions', 'total_pendapatan'));

    }
}
