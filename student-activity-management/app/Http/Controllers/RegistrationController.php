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

        return view('registrations.create', compact('activity', 'isRegistered', 'user'));
    }

    public function store(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $user = Auth::user();

        // Kiểm tra xem người dùng đã đăng ký chưa
        if (Registration::where('activity_id', $activity->id)->where('student_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Bạn đã đăng ký tham gia hoạt động này.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:15',
            'department' => 'required|string|max:255',
            'student_id' => 'required|string|max:255',
            'batch' => 'required|string|max:10',
        ]);

        // Lưu thông tin đăng ký vào bảng registrations (giả sử bạn đã có bảng này)
        $activity->registrations()->create([
            'student_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'activity_id' => $activity->id,
        ]);

        return redirect()->route('activities.register', ['activity' => $activity->id])
            ->with('success', 'Bạn đã đăng ký thành công.');
    }
}
