<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        // Lấy 10 hoạt động mỗi trang
        $activities = Activity::orderBy('date', 'desc')->paginate(10);
        return view('student.activities.index', compact('activities'));
    }

    public function show($id)
    {
        // Tìm hoạt động theo ID
        $activity = Activity::findOrFail($id);

        // Trả về view hiển thị chi tiết hoạt động
        return view('student.activities.show', compact('activity'));
    }
}
