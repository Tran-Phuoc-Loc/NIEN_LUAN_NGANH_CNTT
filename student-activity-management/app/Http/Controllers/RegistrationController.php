<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function create($id)
    {
        $activity = Activity::findOrFail($id);
        $user = Auth::user();

        // Kiểm tra xem người dùng đã đăng ký tham gia hoạt động chưa
        $isRegistered = Registration::where('activity_id', $activity->id)
            ->where('student_id', $user->student_id)
            ->exists();

        return view('student.registrations.create', compact('activity', 'isRegistered', 'user'));
    }

    public function store(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $user = Auth::user();

        // Kiểm tra xem người dùng đã đăng ký chưa
        if (Registration::where('activity_id', $activity->id)
            ->where('student_id', $user->student_id)  // Sử dụng student_id
            ->exists()
        ) {
            return redirect()->back()->with('error', 'Bạn đã đăng ký tham gia hoạt động này.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:15',
            'student_id' => 'required|string|max:255',
        ]);

        $studentId = Auth::user()->student_id;
        // Lưu thông tin đăng ký vào bảng registrations (giả sử bạn đã có bảng này)
        $activity->registrations()->create([
            'student_id' => $studentId,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'activity_id' => $activity->id,
        ]);

        return redirect()->route('registrations.create', ['id' => $activity->id])
            ->with('success', 'Bạn đã đăng ký thành công.');
    }
}
