<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý yêu cầu đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->only('student_id', 'password');

        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công
            return $this->authenticated($request, Auth::user());
        }

        // Đăng nhập thất bại
        return back()->withErrors([
            'student_id' => 'Mã số sinh viên hoặc mật khẩu không đúng.',
        ]);
    }

    // Xử lý sau khi đăng nhập thành công
    protected function authenticated(Request $request, $user)
    {
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'user') {
            return redirect()->route('student.dashboard');
        } else {
            // Redirect đến trang mặc định nếu không phải sinh viên hoặc admin
            return redirect()->route('home');

            return redirect('/home'); // Hoặc trang chính cho người dùng thông thường
        }
    }

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::logout();
        return redirect('/login'); // Hoặc bất kỳ trang nào khác sau khi đăng xuất
    }
}
