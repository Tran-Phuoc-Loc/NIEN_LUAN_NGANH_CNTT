<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Activity;
use App\Models\StudentIssue;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Jobs\UpdateStudentUserId;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        // Truy xuất dữ liệu từ cơ sở dữ liệu 
        $totalMembers = User::count();  // Tổng số thành viên 
        $totalActivities = Activity::count();  // Tổng số hoạt động
        $visibleActivitiesCount = Activity::where('is_hidden', 0)->count(); // Đếm số hoạt động không ẩn
        $recentActivities = Activity::withCount('registrations')->latest()->take(3)->get();
        $studentIssues = StudentIssue::orderBy('created_at', 'desc')->take(5)->get();


        return view('admin.dashboard', compact('totalMembers', 'totalActivities', 'visibleActivitiesCount', 'recentActivities', 'studentIssues'));
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
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:xlsx,csv',
            ]);

            $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray();

            foreach ($data as $index => $row) {
                if ($index == 0 || empty($row[0])) {
                    continue; // Bỏ qua tiêu đề và dòng rỗng
                }

                try {
                    // Lưu dữ liệu vào bảng users
                    $user = User::updateOrCreate(
                        ['email' => $row[2]], // Điều kiện tìm kiếm
                        [
                            'name' => $row[1],
                            'password' => bcrypt($row[6]),
                            'student_id' => $row[0],
                        ]
                    );

                    // Lưu dữ liệu vào bảng students
                    Student::updateOrCreate(
                        ['student_id' => $row[0]], // Điều kiện tìm kiếm để không tạo trùng lặp
                        [
                            'name' => $row[1],
                            'email' => $row[2],
                            'phone' => $row[3],
                            'class' => $row[4],
                            'department' => $row[5],
                            'user_id' => $user->id, // Gán user_id cho sinh viên
                        ]
                    );

                    // Dispatch job để cập nhật user_id
                    UpdateStudentUserId::dispatch($row[0]); // student_id từ file
                } catch (\Exception $e) {
                    // Ghi log hoặc xử lý lỗi khác nếu cần

                    Log::error('Lỗi khi nhập dữ liệu: ' . $e->getMessage());
                }
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
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(student_id) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(department) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(class) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Sắp xếp theo tên
        if ($request->has('sort') && $request->input('sort') != '') {
            if ($request->input('sort') == 'name_asc') {
                $query->orderBy('name', 'asc');
            } elseif ($request->input('sort') == 'name_desc') {
                $query->orderBy('name', 'desc');
            }
        }

        // Sắp xếp theo email
        if ($request->has('sort') && $request->input('sort') != '') {
            if ($request->input('sort') == 'email_asc') {
                $query->orderBy('email', 'asc');
            } elseif ($request->input('sort') == 'email_desc') {
                $query->orderBy('email', 'desc');
            }
        }

        // Lấy danh sách sinh viên
        $students = $query->get();


        // dd($students, $departments, $classes);
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
