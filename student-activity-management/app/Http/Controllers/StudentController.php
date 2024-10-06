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
        //   // Kiểm tra ID người dùng
    
        // Lấy danh sách hoạt động sắp tới từ bảng 'activities'
        $upcoming_activities = Activity::where('date', '>=', now())->get();
        // dd($upcoming_activities); // Kiểm tra danh sách hoạt động sắp tới
    
        // Lấy các thông báo cho người dùng hiện tại
        $notifications = Notification::where('student_id', $user->id)->get();
    
        // Khai báo mảng để lưu hoạt động mà sinh viên đã tham gia
        $participated_activities = [];
    
        // Kiểm tra xem sinh viên đã tham gia vào mỗi hoạt động sắp tới hay chưa
        foreach ($upcoming_activities as $activity) {
            $participation = DB::table('registrations')
                ->where('student_id', $user->id)
                ->where('activity_id', $activity->id)
                ->first();
    
            // Debug thông tin tham gia
            if ($participation) {
                $participated_activities[] = $activity->name; // Giả sử tên hoạt động là trường 'name'
            } else {
                // Nếu không tham gia, in ra thông tin
                // dd("Không tham gia hoạt động ID: " . $activity->id);
            }
        }
        Log::info('Participated Activities: ', $participated_activities);
        // Trả về view với dữ liệu hoạt động và thông tin người dùng
        return view('student.dashboard', compact('user', 'upcoming_activities', 'notifications', 'participated_activities'));
    }
}
