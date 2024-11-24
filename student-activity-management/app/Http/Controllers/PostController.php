<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();

        // Tìm kiếm theo tiêu đề hoặc nội dung
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
    
        // Lọc theo người đăng
        if ($request->has('author') && $request->author) {
            $query->where('user_id', $request->author);
        }
    
        $posts = $query->with('user')->latest()->paginate(9); // Sử dụng phân trang
    
        // Lấy danh sách người đăng
        $authors = User::has('posts')->get();
        return view('student.posts.index', compact('posts', 'authors'));
    }

    public function create()
    {
        return view('student.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:9048',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('posts', 'public') : null;

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Bài viết đã được đăng.');
    }

    public function show($id)
    {
        $post = Post::with(['comments.user'])->findOrFail($id);
        return view('student.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        // Kiểm tra quyền sở hữu
        if (auth::id() !== $post->user_id) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        return view('student.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Kiểm tra quyền sở hữu
        if (auth::id() !== $post->user_id) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        // Validate dữ liệu
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Cập nhật bài viết
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $request->hasFile('image')
                ? $request->file('image')->store('images', 'public')
                : $post->image,
        ]);

        return redirect()->route('posts.show', $post->id)->with('success', 'Bài viết đã được cập nhật thành công.');
    }

    public function destroy(Post $post)
    {
        // Kiểm tra quyền sở hữu
        if (auth::id() !== $post->user_id) {
            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        // Xóa bài viết
        $post->delete();

        return redirect()->route('student.posts.index')->with('success', 'Bài viết đã được xóa.');
    }
}
