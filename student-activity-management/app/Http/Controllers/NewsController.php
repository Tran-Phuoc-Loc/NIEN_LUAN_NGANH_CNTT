<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    // Hiển thị danh sách các tin tức
    public function index(Request $request)
    {
        // Lấy giá trị tìm kiếm, ngày đăng và sắp xếp
        $search = $request->input('search');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $sort_by = $request->input('sort_by', 'newest'); // Mặc định sắp xếp theo mới nhất

        // Truy vấn và áp dụng bộ lọc
        $news = News::query()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                return $query->whereBetween('published_at', [$from_date, $to_date]);
            })
            ->when($sort_by == 'newest', function ($query) {
                return $query->orderBy('created_at', 'desc'); // Mới nhất
            })
            ->when($sort_by == 'oldest', function ($query) {
                return $query->orderBy('published_at', 'asc'); // Cũ nhất
            })
            ->when($sort_by == 'alphabetical', function ($query) {
                return $query->orderBy('title', 'asc'); // Sắp xếp theo chữ cái
            })
            ->paginate(6); // Điều chỉnh số lượng trang hiển thị mỗi lần

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
