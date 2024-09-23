<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminController extends Controller
{
    public function index()
    {
        // trả về admin
        return view('admin.dashboard');
    }

    // Hiển thị form thêm sinh viên
    public function create()
    {
        return view('admin.managers.create');
    }

    // Xử lý việc lưu sinh viên
    public function store(Request $request)
    {
        try {
            // Xử lý form nhập tay
            if ($request->has('student_id')) {
                $validatedData = $request->validate([
                    'student_id.*' => 'required|string|max:255|unique:students,student_id',
                    'name.*' => 'required|string|max:255',
                    'email.*' => 'required|email|unique:users,email',  // Thêm email
                    'phone.*' => 'nullable|string|max:255',
                    'class.*' => 'nullable|string|max:255',
                    'department.*' => 'nullable|string|max:255',
                    'password.*' => 'required|string|min:6',
                ]);

                foreach ($validatedData['student_id'] as $key => $studentId) {
                    // Lưu vào bảng students
                    Student::create([
                        'student_id' => $studentId,
                        'name' => $validatedData['name'][$key],
                        'email' => $validatedData['email'][$key],
                        'phone' => $validatedData['phone'][$key],
                        'class' => $validatedData['class'][$key],
                        'department' => $validatedData['department'][$key],
                    ]);

                    // Lưu vào bảng users
                    User::create([
                        'name' => $validatedData['name'][$key],
                        'email' => $validatedData['email'][$key],
                        'password' => bcrypt($validatedData['password'][$key]),
                        'student_id' => $studentId,
                    ]);
                }
            }

            // Xử lý import file CSV/Excel
            if ($request->hasFile('file')) {
                return $this->import($request);
            }

            return redirect()->route('admin.managers.create')->with('success', 'Sinh viên đã được thêm thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        // Kiểm tra xem có file được upload không
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:xlsx,csv',
            ]);

            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray();

            foreach ($data as $index => $row) {
                // Bỏ qua tiêu đề (dòng đầu tiên)
                if ($index == 0 || empty($row[0])) {
                    continue;
                }

                // Lưu dữ liệu vào bảng students
                Student::create([
                    'student_id' => $row[0],
                    'name' => $row[1],
                    'email' => $row[2],
                    'phone' => $row[3],
                    'class' => $row[4],
                    'department' => $row[5],
                ]);

                // Lưu dữ liệu vào bảng users
                User::create([
                    'name' => $row[1],
                    'email' => $row[2],
                    'password' => bcrypt($row[6]),
                    'student_id' => $row[0], // Hoặc thêm một trường khóa ngoại cho sinh viên
                ]);
            }

            return redirect()->route('admin.managers.create')->with('success', 'Dữ liệu sinh viên đã được import thành công!');
        }

        return redirect()->back()->with('error', 'Không có file nào được upload.');
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
        return view('admin.managers.students', compact('students'));
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

        // Tìm bản ghi liên quan trong bảng students và xóa
        $student = Student::where('student_id', $user->student_id)->first(); // Giả sử bạn có trường student_id trong bảng users
        if ($student) {
            $student->delete(); // Xóa bản ghi trong bảng students
        }

        // Xóa bản ghi trong bảng users
        $user->delete();
        return redirect()->route('admin.managers.index')->with('success', 'Quản lý đã được xóa.');
    }
}
