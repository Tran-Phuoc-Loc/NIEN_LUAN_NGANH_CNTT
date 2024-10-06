<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class PasswordResetController extends Controller
{
    public function showResetRequestForm()
    {
        return view('auth.passwords.email'); // Giao diện nhập email
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:students,email']);

        $token = Str::random(60);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        Mail::send('auth.passwords.email_template', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Đặt Lại Mật Khẩu');
        });

        return back()->with('success', 'Đường dẫn đặt lại mật khẩu đã được gửi đến email của bạn.');
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]); // Giao diện nhập mật khẩu mới
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:students,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $updatePassword = DB::table('password_resets')->where(['email' => $request->email])->first();

        if (!$updatePassword) {
            return back()->withInput()->withErrors(['email' => 'Email không tồn tại!']);
        }

        // Cập nhật mật khẩu mới
        Student::where('email', $request->email)->update(['password' => bcrypt($request->password)]);

        // Xóa token
        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại thành công!');
    }
}