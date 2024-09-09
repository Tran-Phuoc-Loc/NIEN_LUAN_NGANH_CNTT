<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

         // Lấy thông tin sinh viên liên kết với người dùng hiện tại
        $student = $user->student;// Sử dụng mối quan hệ đã định nghĩa trong model User

        return view('profile.show', compact('user', 'student'));
    }
}
