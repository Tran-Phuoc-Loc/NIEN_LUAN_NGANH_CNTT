<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        return view('student.dashboard');
    }
    public function dashboard()
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();        

        // Kiểm tra xem người dùng có tồn tại không
        if (!$user) {
            // Xử lý trường hợp không có người dùng (nếu cần)
            abort(403, 'Unauthorized action.');
        }
        // Dữ liệu giả lập khác
        $upcoming_activities = [
            ['name' => 'Hội thảo Kỹ năng lãnh đạo', 'date' => '10/09/2024', 'location' => 'Hội trường A'],
            ['name' => 'Chiến dịch tình nguyện mùa đông', 'date' => '15/12/2024 - 20/12/2024', 'location' => 'Tỉnh Lào Cai']
        ];

        $notifications = [
            ['content' => 'Hạn chót đăng ký tham gia Hội thảo Kỹ năng lãnh đạo: 05/09/2024', 'type' => 'info'],
            ['content' => 'Kết quả cuộc thi Ý tưởng sáng tạo đã được công bố', 'type' => 'success']
        ];

        $activity_stats = [
            ['month' => 'T1', 'count' => 4],
            ['month' => 'T2', 'count' => 3],
            ['month' => 'T3', 'count' => 5],
            ['month' => 'T4', 'count' => 2],
            ['month' => 'T5', 'count' => 6],
            ['month' => 'T6', 'count' => 4]
        ];

        // Truyền dữ liệu vào view
        return view('student.dashboard', compact('user', 'upcoming_activities', 'notifications', 'activity_stats'));
    }
}
