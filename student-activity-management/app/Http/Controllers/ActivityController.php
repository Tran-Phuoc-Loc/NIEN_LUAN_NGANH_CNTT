<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::all();
        return view('activities.index', compact('activities'));
    }
    // xử lý tạo hoạt đông mới
    public function create()
    {
        return view('activities.create');
    }
    // xử lý việc gửi biểu mẫu và lưu trữ hoạt động mới trong csdl
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date',
        ]);

        Activity::create($request->all());

        return redirect()->route('activities.index')->with('success', 'Hoạt động đã được tạo thành công.');
    }
    // truy xuất và hiển thị chỉnh sửa
    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        return view('activities.edit', compact('activity'));
    }
    // xử lý biểu mẫu đã chỉnh sửa và cập nhật
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date',
        ]);

        $activity = Activity::findOrFail($id);
        $activity->update($request->all());

        return redirect()->route('activities.index')->with('success', 'Hoạt động đã được cập nhật.');
    }
    // xóa hoạt động
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Hoạt động đã được xóa.');
    }

    public function show($id)
    {
        // Tìm hoạt động theo ID
        $activity = Activity::findOrFail($id);

        // Trả về view hiển thị chi tiết hoạt động
        return view('activities.show', compact('activity'));
    }
}
