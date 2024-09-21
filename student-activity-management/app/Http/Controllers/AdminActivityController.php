<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class AdminActivityController extends Controller
{
    // Hiển thị danh sách các hoạt động cho admin quản lý
    public function index(Request $request)
    {
        $query = Activity::query();

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $activities = $query->paginate(10); // Hiển thị 10 hoạt động mỗi trang
        return view('admin.activities.index', compact('activities'));
    }

    // Hiển thị form thêm hoạt động
    public function create()
    {
        return view('admin.activities.create');
    }

    // Hiển thị form chỉnh sửa hoạt động
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        return view('admin.activities.edit', compact('activity'));
    }

    // Xử lý cập nhật hoạt động
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'benefits' => 'required|string',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after_or_equal:registration_start',
        ]);

        $activity = Activity::findOrFail($id);
        $activity->update($validatedData);

        return redirect()->route('admin.activities.index')->with('success', 'Hoạt động đã được cập nhật thành công!');
    }

    // Xử lý thêm hoạt động
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'benefits' => 'required|string',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after_or_equal:registration_start',
        ]);

        Activity::create($validatedData);

        return redirect()->route('admin.activities.index')->with('success', 'Hoạt động đã được thêm thành công!');
    }

    // Xóa hoạt động
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('admin.activities.index')->with('success', 'Hoạt động đã được xóa thành công!');
    }
}
