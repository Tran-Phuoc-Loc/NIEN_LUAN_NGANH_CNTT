<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // trả về admin
        return view('admin.dashboard');
    }

    // Hiển thị danh sách sinh viên cho admin
    public function showStudents()
    {
        // Kiểm tra xem người dùng có quyền admin không
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'lỗi.');
        }

        // Lấy danh sách sinh viên
        $students = Student::all();

        // Trả về view hiển thị danh sách sinh viên
        return view('admin.students', compact('students'));
    }

    // public function showDashboard()
    // {
    //     $months = ['January', 'February', 'March', 'April', 'May']; // Thay thế bằng dữ liệu thực tế
    //     $registrations = [10, 20, 15, 30, 25]; // Thay thế bằng dữ liệu thực tế

    //     return view('admin.dashboard', compact('months', 'registrations'));
    // }
}
