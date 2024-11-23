<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentIssue;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function index()
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user()->student;
        // Lấy danh sách thông báo của sinh viên
        $notifications = Notification::where('user_id', Auth::id())->orderBy('created_at', 'desc')
        ->get();

        // Chuyển hướng đến view
        return view('student.issues.index', compact('notifications'))->with('student', $user);
    }

    public function store(Request $request)
    {
        // Kiểm tra dữ liệu
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Lấy sinh viên viện hiện tại
        $student = Auth::user();

        // Lưu thắc mắc vào cơ sở dữ liệu
        StudentIssue::create([
            'student_id' => $student->id, // Giả sử bạn có cột student_id trong bảng users
            'student_name' => $student->name, // Giả sử bảng users có trường name
            'student_email' => $student->email,
            'message' => $request->message,
            'is_resolved' => 0, // Thêm giá trị cho cột is_resolved
        ]);

        // Chuyển hướng sau khi lưu thành công
        return redirect()->back()->with('success', 'Câu hỏi của bạn đã được gửi.');
    }
     // Đánh dấu thông báo là đã đọc
     public function markAsRead($notificationId)
     {
         $notification = Notification::findOrFail($notificationId);
 
         if (!$notification->is_read) {
             $notification->is_read = true;
             $notification->save();
         }
 
         return redirect()->route('student.issues.index');
     }
}
