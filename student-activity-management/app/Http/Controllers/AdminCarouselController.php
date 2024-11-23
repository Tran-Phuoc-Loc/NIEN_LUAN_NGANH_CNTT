<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarouselImage;
use Illuminate\Support\Facades\Storage;

class AdminCarouselController extends Controller
{
    public function create()
    {
        return view('admin.carousel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Mỗi ảnh phải là tệp ảnh
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Xóa ảnh cũ
        $oldImages = CarouselImage::all();
        foreach ($oldImages as $oldImage) {
            // Xóa file khỏi storage
            Storage::disk('public')->delete($oldImage->path);
            // Xóa bản ghi khỏi database
            $oldImage->delete();
        }

        // Lưu ảnh mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('carousel', 'public');

                // Tạo bản ghi mới trong database
                CarouselImage::create([
                    'path' => $path,
                    'title' => $request->title,
                    'description' => $request->description,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Ảnh mới đã được thêm và ảnh cũ đã bị xóa.');
    }
}
