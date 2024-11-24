<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]); 

        Comment::create([
            'comment' => $request->comment,
            'post_id' => $post->id,
            'user_id' => auth::id(),
        ]);

        return back()->with('success', 'Bình luận đã được đăng!');
    }
}
