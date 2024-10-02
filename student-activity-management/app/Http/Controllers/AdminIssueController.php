<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentIssue;
use App\Models\User;
use App\Models\Student;
use App\Models\Notification;

class AdminIssueController extends Controller
{
    // Hiển thị tất cả vấn đề của học sinh
    public function index()
    {
        $studentIssues = StudentIssue::orderBy('created_at', 'desc')->paginate(10); // Paginate the results
        return view('admin.issues.index', compact('studentIssues'));
    }

    // Phương thức xử lý vấn đề
    public function resolve($id)
    {
        // Tìm vấn đề theo ID
        $issue = StudentIssue::find($id);

        // Kiểm tra xem vấn đề có tồn tại hay không
        if (!$issue) {
            return redirect()->route('admin.issues.index')->with('error', 'Không tìm thấy thông báo.');
        }

        // Đánh dấu vấn đề là đã được xử lý
        $issue->is_resolved = true;
        $issue->save();

        // Chuyển hướng về danh sách vấn đề với thông báo thành công
        return redirect()->route('admin.issues.index')->with('success', 'Thông báo đã được xử lý thành công.');
    }

    public function send(Request $request)
    {        
        // Truy vấn sinh viên có vai trò là "student" và lấy dữ liệu từ cả hai bảng
        $query = User::with('student') // Eager load mối quan hệ student
            ->where('role', 'student');

        // Nếu có từ khóa tìm kiếm, thêm điều kiện vào truy vấn
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }
        $students = $query->get();

        // Kiểm tra dữ liệu
        if ($students->isEmpty()) {
            return redirect()->route('admin.issues.send')
                ->with('error', 'Không tìm thấy sinh viên nào.');
        }
        // dd($students);
        return view('admin.issues.send', compact('students'));
    }

    public function storeSend(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        // Lưu thông báo cho từng sinh viên
        foreach ($request->student_ids as $studentId) {
            Notification::create([
                'student_id' => $studentId,
                'message' => $request->message,
            ]);
        }

        return redirect()->route('admin.issues.index')->with('success', 'Thông báo đã được gửi thành công đến sinh viên!');
    }
}
