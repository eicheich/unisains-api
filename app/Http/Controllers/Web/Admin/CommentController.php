<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function all()
    {
        $comment = Rate::with(['user', 'course'])->orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.comment.all', compact('comment'));

    }
}
