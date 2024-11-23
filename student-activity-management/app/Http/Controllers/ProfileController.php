<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

        // Thay vì lấy ID, lấy student_id
        $studentId = $user->student_id; // Giả sử trường này lưu student_id là MSV2

        // Lấy danh sách hoạt động sắp tới từ bảng 'activities'
        $upcoming_activities = Activity::where('date', '>=', now())->get();

        // Khai báo mảng để lưu hoạt động mà sinh viên đã tham gia
        $participated_activities = [];

        // Lấy thông tin sinh viên liên kết với người dùng hiện tại
        $student = $user->student; // Sử dụng mối quan hệ đã định nghĩa trong model User
        
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
        return view('profile.show', compact('user', 'student', 'participated_activities'));
    }
}
