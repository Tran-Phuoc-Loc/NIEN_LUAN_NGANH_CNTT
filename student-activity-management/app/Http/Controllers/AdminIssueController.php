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
        // Kiểm tra nếu người dùng không nhập từ khóa tìm kiếm
        if (!$request->has('search') || trim($request->search) === '') {
            // Trả về danh sách tất cả sinh viên
            $allStudents = Student::all();
            return view('admin.issues.send', ['allStudents' => $allStudents]);
        }

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

        // Phân trang kết quả
        $students = $query->paginate(10);

        // Kiểm tra nếu không tìm thấy kết quả từ tìm kiếm
        if ($students->isEmpty()) {
            // Trả về danh sách tất cả sinh viên
            $allStudents = Student::all();
            return view('admin.issues.send', [
                'error' => 'Không tìm thấy sinh viên nào theo từ khóa, đây là danh sách tất cả sinh viên.',
                'allStudents' => $allStudents
            ]);
        }

        // Trả về kết quả tìm kiếm
        return view('admin.issues.send', compact('students'));
    }

    public function storeSend(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'student_ids' => 'array|nullable', // Cho phép null nếu gửi đến tất cả
        ]);

        // Kiểm tra xem có gửi đến tất cả sinh viên không
        if ($request->has('send_to_all')) {
            // Lấy tất cả sinh viên
            $students = Student::all();
        } else {
            // Lấy sinh viên được chọn
            $students = Student::whereIn('id', $request->student_ids)->get();
        }

        // Lưu thông báo cho từng sinh viên
        foreach ($students as $student) {
            Notification::create([
                'student_id' => $student->id,
                'message' => $request->message,
            ]);
        }

        return redirect()->route('admin.issues.index')->with('success', 'Thông báo đã được gửi thành công đến sinh viên!');
    }
}
