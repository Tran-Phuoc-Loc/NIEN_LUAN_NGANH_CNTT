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
use Carbon\Carbon;

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
                    'joining_date.*' => 'nullable|string|max:255',
                    'card_issuing_place.*' => 'nullable|string|max:255',
                    'password.*' => 'required|string|min:6',
                ]);

                foreach ($validatedData['student_id'] as $key => $studentId) {
                    // Lưu vào bảng users
                    $user = User::create([
                        'name' => $validatedData['name'][$key],
                        'email' => $validatedData['email'][$key],
                        'password' => bcrypt($validatedData['password'][$key]),
                        'role' => 'user',
                        'student_id' => $studentId,
                    ]);

                    // Lưu vào bảng students
                    Student::create([
                        'student_id' => $studentId,
                        'name' => $validatedData['name'][$key],
                        'email' => $validatedData['email'][$key],
                        'phone' => $validatedData['phone'][$key],
                        'joining_date' => $validatedData['joining_date'][$key],
                        'card_issuing_place' => $validatedData['card_issuing_place'][$key],
                        'user_id' => $user->id, // Liên kết với user đã tạo
                    ]);
                    // dd($user->id); // Kiểm tra giá trị user_id trước khi lưu

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
                    // Chuyển đổi ngày từ định dạng Excel nếu cần thiết
                    $joiningDate = $row[4]; // Giả sử cột ngày ở vị trí thứ 5 trong file Excel
                    if (!empty($joiningDate)) {
                        try {
                            // Chuyển đổi định dạng ngày từ Excel (thường là 'd/m/Y') sang 'Y-m-d'
                            $joiningDate = Carbon::createFromFormat('d/m/Y', $joiningDate)->format('Y-m-d');
                        } catch (\Exception $e) {
                            // Nếu có lỗi trong chuyển đổi, bỏ qua hoặc ghi log
                            Log::error('Lỗi khi chuyển đổi ngày: ' . $e->getMessage());
                            $joiningDate = null;
                        }
                    }

                    // Lưu dữ liệu vào bảng users
                    $user = User::updateOrCreate(
                        ['email' => $row[2]], // Điều kiện tìm kiếm
                        [
                            'name' => $row[1],
                            'password' => bcrypt($row[6]),
                            'role' => 'user',
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
                            'joining_date' => $joiningDate, // Sử dụng ngày đã được chuyển đổi
                            'card_issuing_place' => $row[5],
                            'user_id' => $user->id, // Gán user_id cho sinh viên
                        ]
                    );
                } catch (\Exception $e) {
                    Log::error('Lỗi khi nhập dữ liệu từ file: ' . $e->getMessage());
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
                    ->orWhereRaw('LOWER(card_issuing_place) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(joining_date) LIKE ?', ['%' . strtolower($search) . '%']);
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

        // Kiểm tra nếu người dùng hiện tại là admin chủ
        if (Auth::user()->is_super_admin) {
            // Nếu là admin chủ, lấy tất cả người dùng bao gồm cả admin chủ
            $users = $query->orderByRaw("role = 'admin' DESC")->get();
        } else {
            // Nếu không phải admin chủ, loại trừ admin chủ
            $users = $query->where('is_super_admin', false)
                ->orderByRaw("role = 'admin' DESC")
                ->get();
        }

        return view('admin.managers.index', compact('users'));
    }

    // Cập nhật quyền cho người dùng
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        $currentUser = Auth::user();

        // Log thông tin người dùng hiện tại và vai trò yêu cầu
        // Log::info('Current User:', ['id' => $currentUser->id, 'role' => $currentUser->role, 'is_super_admin' => $currentUser->is_super_admin]);
        // Log::info('User to Update:', ['id' => $user->id, 'role' => $user->role]);

        // Kiểm tra nếu người dùng hiện tại là admin chủ
        if ($currentUser->is_super_admin) {
            // Ngăn admin chủ tự hạ quyền
            if ($user->id === $currentUser->id && $request->role === 'user') {
                // Log::warning('Super admin trying to demote themselves', ['super_admin_id' => $currentUser->id]);
                return redirect()->route('admin.managers.index')->with('error', 'Admin chủ không thể tự hạ quyền của mình.');
            }

            // Cập nhật vai trò cho người dùng
            $user->role = $request->role;
            $user->save();

            // Log::info('Role updated by super admin', ['user_id' => $user->id, 'new_role' => $request->role]);
            return redirect()->route('admin.managers.index')->with('success', 'Quyền đã được cập nhật thành công');
        }

        // Kiểm tra nếu người dùng hiện tại là admin nhưng không phải admin chủ
        if ($currentUser->role === 'admin') {
            // Ngăn người dùng admin không thể hạ quyền admin khác
            if ($user->role === 'admin' && $request->role === 'user') {
                // Log::warning('Admin trying to demote another admin', ['admin_id' => $currentUser->id, 'demoted_admin_id' => $user->id]);
                return redirect()->route('admin.managers.index')->with('error', 'Bạn không thể hạ quyền một admin khác.');
            }

            // Kiểm tra nếu người dùng đang cố gắng tự chuyển xuống vai trò user
            if ($user->id === $currentUser->id && $request->role === 'user') {
                // Log::warning('Admin trying to demote themselves', ['admin_id' => $currentUser->id]);
                return redirect()->route('admin.managers.index')->with('error', 'Bạn không thể tự chuyển đổi vai trò của mình thành người dùng.');
            }

            return redirect()->route('admin.managers.index')->with('error', 'Bạn không có quyền cấp quyền cho người khác.');
        }

        // Nếu không có trường hợp nào được xử lý
        return redirect()->route('admin.managers.index')->with('error', 'Hành động không hợp lệ.');
    }

    // Xóa người dùng
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        // Kiểm tra xem người dùng hiện tại có phải là admin chủ không
        if ($currentUser->is_super_admin) {
            // Tìm bản ghi liên quan trong bảng students và xóa
            $student = Student::where('student_id', $user->student_id)->first(); // Trường student_id trong bảng users
            if ($student) {
                $student->delete(); // Xóa bản ghi trong bảng students
            }

            // Xóa bản ghi trong bảng users
            $user->delete();
            return redirect()->route('admin.managers.index')->with('success', 'Quản lý đã được xóa.');
        }

        // Kiểm tra nếu người dùng hiện tại là admin
        if ($currentUser->role === 'admin') {
            // Nếu người dùng đang cố gắng xóa chính mình
            if ($currentUser->id === $user->id) {
                return redirect()->route('admin.managers.index')->with('error', 'Không thể xóa tài khoản của chính mình.');
            }

            // Nếu người dùng đang cố gắng xóa một admin khác
            if ($user->role === 'admin') {
                return redirect()->route('admin.managers.index')->with('error', 'Không thể xóa quản trị viên.');
            }

            // Nếu là admin, có thể xóa người dùng thường
            $student = Student::where('student_id', $user->student_id)->first();
            if ($student) {
                $student->delete(); // Xóa bản ghi trong bảng students
            }

            // Xóa bản ghi trong bảng users
            $user->delete();
            return redirect()->route('admin.managers.index')->with('success', 'Người dùng đã được xóa.');
        }

        // Nếu không phải admin hoặc admin chủ, không có quyền xóa
        return redirect()->route('admin.managers.index')->with('error', 'Bạn không có quyền thực hiện hành động này.');
    }

    public function edit($user_id)
    {
        $student = Student::where('user_id', $user_id)->first();

        if (!$student) {
            return redirect()->route('admin.managers.index')->with('error', 'Sinh viên không tồn tại.');
        }

        return view('admin.managers.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . $id,
            'phone' => 'nullable|string|max:15', // Số điện thoại
            'joining_date' => 'nullable|date', // ngày vào đoàn
            'card_issuing_place' => 'nullable|string|max:100', // nơi cấp thẻ
            'role' => 'required|in:user,admin', // Quyền
        ]);

        $student = Student::findOrFail($id);

        // Cập nhật thông tin sinh viên
        $student->update($request->only(['name', 'email', 'phone', 'joining_date', 'card_issuing_place']));

        // Nếu bạn cần cập nhật quyền, bạn cũng nên thêm nó vào đây
        $student->role = $request->role; // Cập nhật quyền
        $student->save();

        return redirect()->route('admin.managers.index')->with('success', 'Thông tin sinh viên đã được cập nhật.');
    }
}
