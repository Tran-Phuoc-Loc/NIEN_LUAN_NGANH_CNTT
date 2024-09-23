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
                    // Lưu vào bảng users
                    $user = User::create([
                        'name' => $validatedData['name'][$key],
                        'email' => $validatedData['email'][$key],
                        'password' => bcrypt($validatedData['password'][$key]),
                        'student_id' => $studentId,
                    ]);

                    // Lưu vào bảng students
                    Student::create([
                        'student_id' => $studentId,
                        'name' => $validatedData['name'][$key],
                        'email' => $validatedData['email'][$key],
                        'phone' => $validatedData['phone'][$key],
                        'class' => $validatedData['class'][$key],
                        'department' => $validatedData['department'][$key],
                        'user_id' => $user->id, // Liên kết với user đã tạo
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

                // Lưu dữ liệu vào bảng users
                $user = User::create([
                    'name' => $row[1],
                    'email' => $row[2],
                    'password' => bcrypt($row[6]),
                    'student_id' => $row[0], // Hoặc thêm một trường khóa ngoại cho sinh viên
                ]);

                // Lưu dữ liệu vào bảng students
                Student::create([
                    'student_id' => $row[0],
                    'name' => $row[1],
                    'email' => $row[2],
                    'phone' => $row[3],
                    'class' => $row[4],
                    'department' => $row[5],
                    'user_id' => $user->id, // Liên kết với user đã tạo
                ]);
            }

            return redirect()->route('admin.managers.create')->with('success', 'Dữ liệu sinh viên đã được import thành công!');
        }

        return redirect()->back()->with('error', 'Không có file nào được upload.');
    }

    // Hiển thị danh sách sinh viên cho admin
    public function showStudents(Request $request)
    {
        $query = Student::query();
        // Kiểm tra xem có từ khóa tìm kiếm không
        if ($request->has('search')) {
            $search = $request->input('search');

            // Tìm kiếm không phân biệt chữ hoa chữ thường
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(student_id) LIKE ?', ['%' . strtolower($search) . '%']);
        }

        // Lấy danh sách sinh viên
        $students = $query->get();

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
    public function showUsers(Request $request)
    {
        $query = User::query();

        // Kiểm tra từ khóa tìm kiếm
        if ($request->has('search')) {
            $search = $request->input('search');

            // Tìm kiếm không phân biệt chữ hoa chữ thường
            $query->whereRaw("name COLLATE utf8mb4_general_ci LIKE ?", ['%' . $search . '%'])
                  ->orWhereRaw("email COLLATE utf8mb4_general_ci LIKE ?", ['%' . $search . '%']);
        }

        // Lấy danh sách người dùng và sắp xếp admin lên trên cùng
        $users = $query->orderByRaw("role = 'admin' DESC")->get();

        return view('admin.managers.index', compact('users'));
    }

    // Cập nhật quyền cho người dùng
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        // Kiểm tra nếu người dùng hiện tại là admin và đang cố gắng tự chuyển xuống vai trò user
        if ($user->id && $request->role === 'user') {
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
