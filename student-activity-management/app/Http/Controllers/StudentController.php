<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

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
        // Lấy danh sách hoạt động sắp tới từ bảng 'activities'
        $upcoming_activities = Activity::where('date', '>=', now())->get();

        // Giả sử bạn có các thông báo, đây chỉ là một mảng trống cho ví dụ
        $notifications = [];

        // Trả về view với dữ liệu hoạt động và thông tin người dùng
        return view('student.dashboard', compact('user', 'upcoming_activities', 'notifications'));
    }
}
