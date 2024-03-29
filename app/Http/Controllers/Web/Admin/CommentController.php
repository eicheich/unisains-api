<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function all()
    {
        $comment = Rate::with(['course', 'user'])->orderBy('created_at', 'DESC')->get();
//        dd($comment);
        return view('admin.comment.all', compact('comment'));

    }

    public function approve($id)
    {
        $comment = Rate::find($id);
        $comment->update([
            'status' => 'approved'
        ]);
        return redirect()->back()->with(['success' => 'Komentar berhasil di approve']);
    }

    public function disapprove($id)
    {
        $comment = Rate::find($id);
        $comment->update([
            'status' => 'pending'
        ]);
        return redirect()->back()->with(['success' => 'Komentar berhasil di disapprove']);

    }

    public function search()
    {
        $query = Rate::query()
            ->with(['course', 'user'])
            ->orderBy('created_at', 'DESC');
        $searchTerm = request('search');
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('comment', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('email', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        }
        $filterStatus = request('filter_status');
        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        $filterDate = request('filter_date');
        if ($filterDate) {
            $query->whereDate('created_at', $filterDate);
        }

        $comment = $query->get();
        return view('admin.comment.all', compact('comment'));
    }

}
