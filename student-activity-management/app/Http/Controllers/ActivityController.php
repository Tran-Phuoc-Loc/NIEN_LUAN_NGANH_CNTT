<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Lấy từ khóa tìm kiếm từ request
        $searchTerm = $request->input('search');

        // Lấy 10 hoạt động mỗi trang, sắp xếp theo ngày và không bị ẩn
        $activities = Activity::where('is_hidden', 0) // Chỉ lấy những hoạt động không bị ẩn
            ->when($searchTerm, function ($query, $searchTerm) {
                return $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy('date', 'desc')
            ->paginate(10);
        return view('student.activities.index', compact('activities', 'searchTerm', 'user'));
    }

    public function show($id)
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user()->student;

        // Tìm hoạt động theo ID
        $activity = Activity::findOrFail($id);

        // Trả về view hiển thị chi tiết hoạt động
        return view('student.activities.show', compact('activity'))->with('student', $user);
    }
}
