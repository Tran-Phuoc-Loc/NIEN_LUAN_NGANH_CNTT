<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    // Hiển thị danh sách các tin tức
    public function index()
    {
        $news = News::orderBy('published_at', 'desc')->get();
        return view('student.news.index', compact('news'));
    }

    // Hiển thị một tin tức cụ thể
    public function show($id)
    {
        // Lấy tin tức cùng với hình ảnh liên quan
        $news = News::with('images')->find($id);

        // Kiểm tra xem tin tức có tồn tại không
        if (!$news) {
            return redirect()->route('admin.news.index')->with('error', 'Tin tức không tồn tại.');
        }

        return view('student.news.show', compact('news'));
    }
}
