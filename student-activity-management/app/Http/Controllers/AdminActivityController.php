<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\UnregisteredAttendance;
use App\Models\Registration;

class AdminActivityController extends Controller
{
    // Hiển thị danh sách các hoạt động cho admin quản lý
    public function index(Request $request)
    {
        // Khởi tạo truy vấn cơ bản
        $query = Activity::query();

        // Kiểm tra nếu có từ khóa tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Thêm đếm số lượng người đăng ký
        $activities = $query->withCount('registrations') // Đếm số người đăng ký
            ->paginate(10); // Hiển thị 10 hoạt động mỗi trang

        // Trả về view với dữ liệu đã phân trang
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
            'max_participants' => 'required|integer|min:0',
        ]);

        $activity = Activity::findOrFail($id);

        // Cập nhật hoạt động với dữ liệu đã xác thực
        $activity->update($validatedData);

        // Thông báo và chuyển hướng về trang danh sách hoạt động
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
            'max_participants' => 'required|integer|min:0',
        ]);

        // Tạo mới hoạt động
        $activity = Activity::create($validatedData);

        // Thông báo và chuyển hướng về trang danh sách hoạt động
        return redirect()->route('admin.activities.index')->with('success', 'Hoạt động đã được thêm thành công!');
    }

    // Xóa hoạt động hoặc Ẩn 
    public function destroyOrHide(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        if ($request->action === 'hide') {
            $activity->is_hidden = true; // Đánh dấu là ẩn
            $activity->save();
            return redirect()->route('admin.activities.index')->with('success', 'Hoạt động đã được ẩn thành công!');
        } elseif ($request->action === 'delete') {
            $activity->delete(); // Xóa hoàn toàn
            return redirect()->route('admin.activities.index')->with('success', 'Hoạt động đã được xóa thành công!');
        } elseif ($request->action === 'show') {
            $activity->is_hidden = false; // Đánh dấu là hiện lại
            $activity->save();
            return redirect()->route('admin.activities.index')->with('success', 'Hoạt động đã được hiển thị lại thành công!');
        }

        return redirect()->route('admin.activities.index')->with('error', 'Hành động không hợp lệ!');
    }

    // Phương thức để hiển thị danh sách người dùng đã đăng ký vào hoạt động
    public function registeredUsers($activity_id)
    {
        $activity = Activity::find($activity_id);
        $registered_users = $activity->registeredUsers;
        $registrations = $activity->registrations;

        return view('admin.activities.registered-users', compact('activity', 'registered_users', 'registrations'));
    }

    public function showUnregisteredAttendances($activityId)
    {
        // Lấy tất cả sinh viên đã điểm danh và đã đăng ký
        $registeredStudents = Registration::where('activity_id', $activityId)->pluck('student_id')->toArray();

        // Lấy tất cả sinh viên không đăng ký từ bảng unregistered_attendances mà chưa điểm danh
        $unregisteredAttendances = UnregisteredAttendance::where('activity_id', $activityId)
            ->whereNotIn('student_id', $registeredStudents)
            ->get();

        return view('admin.activities.unregistered_attendances', compact('unregisteredAttendances'));
    }
}
