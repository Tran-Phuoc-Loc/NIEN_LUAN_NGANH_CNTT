<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\CarouselImage;
use App\Models\Registration;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function dashboard()
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user()->student;

        $carousel_images = CarouselImage::all();

        // Thay vì lấy ID, lấy student_id
        $studentId = $user->student_id; // Giả sử trường này lưu student_id là MSV2

        // Lấy danh sách hoạt động sắp tới từ bảng 'activities' và loại bỏ các hoạt động bị ẩn
        $upcoming_activities = Activity::where('date', '>=', now())
            ->where('is_hidden', false) // Loại bỏ các hoạt động bị ẩn
            ->get();


        // Lấy các thông báo cho người dùng hiện tại
        $notifications = Notification::where('user_id', $user->id) // Sử dụng student_id của người dùng
            ->orderBy('created_at', 'desc')
            ->get();

        // Khai báo mảng để lưu hoạt động mà sinh viên đã tham gia
        $participated_activities = [];

        // Kiểm tra xem sinh viên đã tham gia vào mỗi hoạt động sắp tới hay chưa
        foreach ($upcoming_activities as $activity) {
            $participation = DB::table('registrations')
                ->where('student_id', $studentId) // Sử dụng student_id
                ->where('activity_id', $activity->id)
                ->first();

            // Debug thông tin tham gia
            if ($participation) {
                $participated_activities[] = $activity->name; // Lưu tên hoạt động vào mảng
            }
        }

        // Log::info('Notifications: ', $notifications->toArray());
        // Log::info('Current Student ID: ' . $user->id);
        // Log::info('Participated Activities: ', $participated_activities);
        // Log::info('Current User ID: ' . $user->id); // Kiểm tra ID sinh viên
        // Log::info('Registrations for User ID: ' . json_encode($participated_activities)); // In ra danh sách hoạt động đã tham gia

        // Trả về view với dữ liệu hoạt động và thông tin người dùng
        return view('student.dashboard', compact('carousel_images', 'user', 'upcoming_activities', 'notifications', 'participated_activities'))->with('student', $user);
    }

    public function edit()
    {
        $student = auth::user()->student; // Lấy thông tin sinh viên qua user đăng nhập
        return view('profile.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'joining_date' => 'nullable|date',
            'card_issuing_place' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validate file ảnh
        ]);

        $student = auth::user()->student; // Lấy thông tin sinh viên từ user đăng nhập

        // Xử lý avatar nếu có
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }

            // Lưu avatar mới
            $avatarPath = $request->file('avatar')->store('students/avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        // Cập nhật thông tin
        $student->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Cập nhật thông tin thành công!');
    }
}
