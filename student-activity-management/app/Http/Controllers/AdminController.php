<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
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

    // Hiển thị danh sách người dùng
    public function showUsers()
    {
        // Kiểm tra xem người dùng có quyền admin không
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'lỗi.');
        }

        $users = User::orderByRaw("role = 'admin' DESC")->get(); // Sắp xếp admin lên trên cùng
        return view('admin.managers.index', compact('users'));
    }

    // Cập nhật quyền cho người dùng
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        // Lấy người dùng hiện tại
        $currentUser = auth()->user();

        // Kiểm tra nếu người dùng hiện tại là admin và đang cố gắng tự chuyển xuống vai trò user
        if ($currentUser->id === $user->id && $request->role === 'user') {
            return redirect()->route('admin.managers.index')->with('error', 'Bạn không thể tự chuyển đổi vai trò thành người dùng.');
        }

        // Cập nhật vai trò cho người dùng
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.managers.index')->with('success', 'Quyền đã được cập nhật thành công');
    }

    // Xóa người dùng
    public function destroy(User $user)
    {
        // Kiểm tra xem người dùng hiện tại có phải là admin không
        if (Auth::user()->role === 'admin') {
            // Nếu người dùng đang cố gắng xóa chính mình
            if (Auth::id() === $user->id) {
                return redirect()->route('admin.managers.index')->with('error', 'Không thể xóa tài khoản của chính mình.');
            }

            // Nếu người dùng đang cố gắng xóa một admin khác
            if ($user->role === 'admin') {
                return redirect()->route('admin.managers.index')->with('error', 'Không thể xóa quản trị viên.');
            }
        }

        // Nếu không có vấn đề gì, tiến hành xóa
        $user->delete();
        return redirect()->route('admin.managers.index')->with('success', 'Quản lý đã được xóa.');
    }
}
