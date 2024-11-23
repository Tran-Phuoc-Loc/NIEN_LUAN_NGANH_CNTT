<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Support\Facades\Storage;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::orderBy('created_at', 'desc');

        // Kiểm tra và xử lý tìm kiếm theo tiêu đề hoặc nội dung
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('title', 'like', '%' . $searchTerm . '%');
        }

        // Xử lý tìm kiếm theo ngày
        if ($request->start_date && $request->end_date) {
            // Chuyển đổi định dạng ngày từ dd/mm/yyyy sang yyyy-mm-dd
            $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Phân trang với 10 tin tức trên mỗi trang
        $newsList = $query->paginate(10);

        return view('admin.news.index', compact('newsList'));
    }


    // Hiển thị form tạo tin tức mới
    public function create()
    {
        return view('admin.news.create');
    }

    // Lưu tin tức mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Chỉ định cho ảnh bổ sung
        ]);

        // Lưu tin tức
        $news = new News();
        $news->title = $request->title;
        $news->content = str_replace('<!--break-->', '{{!--break--}}', $request->content); // Thay thế <!--break--> trước khi lưu

        // Lưu ảnh minh họa
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
            $news->image = $imagePath;
        }

        $news->save();

        // Lưu các ảnh bổ sung
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePath = $file->store('news_images', 'public');
                $newsImage = new NewsImage();
                $newsImage->news_id = $news->id;
                $newsImage->image_path = $imagePath;
                $newsImage->save();
            }
        }

        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được tạo thành công!');
    }


    // Hiển thị form chỉnh sửa tin tức
    public function edit($id)
    {
        $news = News::findOrFail($id);
        // Lấy các ảnh phụ đã lưu
        $additionalImages = NewsImage::where('news_id', $news->id)->get();
        return view('admin.news.edit', compact('news', 'additionalImages'));
    }

    // Cập nhật tin tức
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Ảnh bổ sung
        ]);

        // Tìm tin tức cần cập nhật
        $news = News::findOrFail($id);
        $news->title = $validated['title'];
        $news->content = str_replace('<!--break-->', '{{!--break--}}', $validated['content']); // Thay thế <!--break--> trước khi lưu

        // Nếu có ảnh mới được tải lên (ảnh chính)
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            // Lưu ảnh mới
            $imagePath = $request->file('image')->store('news_images', 'public');
            $news->image = $imagePath;
        }

        $news->save();

        // Xử lý cập nhật các ảnh bổ sung
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePath = $file->store('news_images', 'public');
                $newsImage = new NewsImage();
                $newsImage->news_id = $news->id;
                $newsImage->image_path = $imagePath;
                $newsImage->save();
            }
        }

        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được cập nhật thành công!');
    }

    // Phương thức xóa tin tức
    public function destroy($id)
    {
        // Tìm tin tức theo ID
        $news = News::findOrFail($id);

        // Nếu tin tức có ảnh, thì xóa ảnh khỏi storage
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }

        // Xóa tin tức
        $news->delete();

        // Điều hướng về trang danh sách tin tức với thông báo thành công
        return redirect()->route('admin.news.index')->with('success', 'Xóa tin tức thành công');
    }
}
