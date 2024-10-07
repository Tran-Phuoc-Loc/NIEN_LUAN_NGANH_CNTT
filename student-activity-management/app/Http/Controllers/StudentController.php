<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\Registration;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function dashboard()
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Thay vì lấy ID, lấy student_id
        $studentId = $user->student_id; // Giả sử trường này lưu student_id là MSV2

        // Lấy danh sách hoạt động sắp tới từ bảng 'activities'
        $upcoming_activities = Activity::where('date', '>=', now())->get();

        // Lấy các thông báo cho người dùng hiện tại
        $notifications = Notification::where('student_id', $user->id)->orderBy('created_at', 'desc')
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

        // Log::info('Participated Activities: ', $participated_activities);
        // Log::info('Current User ID: ' . $user->id); // Kiểm tra ID sinh viên
        // Log::info('Registrations for User ID: ' . json_encode($participated_activities)); // In ra danh sách hoạt động đã tham gia

        // Trả về view với dữ liệu hoạt động và thông tin người dùng
        return view('student.dashboard', compact('user', 'upcoming_activities', 'notifications', 'participated_activities'));
    }
}
