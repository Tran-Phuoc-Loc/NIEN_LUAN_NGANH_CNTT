<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentIssue;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminIssueController extends Controller
{
    public function index()
    {
        // Lấy danh sách vấn đề từ sinh viên (chưa xử lý và đã xử lý)
        $studentIssues = StudentIssue::orderBy('created_at', 'desc')->paginate(10);

        // Lấy danh sách thông báo của admin, nhóm theo nội dung thông báo (message)
        $adminNotifications = Notification::with(['student']) // eager load quan hệ với Student
            ->where('is_admin', 1)
            ->select(DB::raw('MIN(id) as id'), 'message', DB::raw('count(*) as total'), DB::raw('MAX(created_at) as created_at'))
            ->groupBy('message') // Chỉ nhóm theo message
            ->orderBy('created_at', 'desc') // Lưu ý: cần MAX(created_at) ở trên
            ->paginate(10);

        // Lấy danh sách sinh viên cho mỗi thông báo
        foreach ($adminNotifications as $notification) {
            $notification->students = Notification::where('message', $notification->message)
                ->where('is_admin', 1)
                ->with('student') // Đảm bảo tải dữ liệu sinh viên
                ->get()
                ->map(function ($item) {
                    return $item->student; // Trả về chỉ đối tượng student
                });
        }

        // dd($adminNotifications);
        return view('admin.issues.index', compact('studentIssues', 'adminNotifications'));
    }

    // Phương thức xử lý vấn đề
    public function resolve($id)
    {
        $issue = StudentIssue::find($id);

        if (!$issue) {
            return redirect()->route('admin.issues.index')->with('error', 'Không tìm thấy thông báo.');
        }

        $issue->is_resolved = true;
        $issue->save();

        return redirect()->route('admin.issues.index')->with('success', 'Thông báo đã được xử lý thành công.');
    }

    public function send(Request $request)
    {
        $users = null;

        if (!$request->has('search') || trim($request->search) === '') {
            $allUsers = User::where('role', 'user')->paginate(10);
            return view('admin.issues.send', ['allUsers' => $allUsers, 'users' => $users]);
        }

        $query = User::where('role', 'user');

        // Nếu có từ khóa tìm kiếm, thêm điều kiện vào truy vấn
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10);

        if ($users->isEmpty()) {
            $allUsers = User::where('role', 'user')->paginate(10);
            return view('admin.issues.send', [
                'error' => 'Không tìm thấy người dùng theo từ khóa, đây là danh sách tất cả người dùng.',
                'allUsers' => $allUsers,
                'users' => $users
            ]);
        }

        return view('admin.issues.send', compact('users'));
    }

    public function storeSend(Request $request)
    {
        $message = $request->input('message');
        $sendOption = $request->input('send_option');

        $users = collect();
        $sendToAll = false;
        $sendToGroup = false;

        if ($sendOption === 'all') {
            $users = User::where('role', 'user')->get();
            $sendToAll = true;
        } elseif ($sendOption === 'group') {
            $userIds = $request->input('user_ids', []);
            if (count($userIds) >= 50) {
                $users = User::whereIn('id', $userIds)->get();
                $sendToGroup = true;
            } else {
                return redirect()->back()->with('error', 'Bạn cần chọn ít nhất 50 người dùng để gửi nhóm lớn.');
            }
        } elseif ($sendOption === 'selected') {
            $userIds = $request->input('user_ids', []);
            if (count($userIds) > 0) {
                $users = User::whereIn('id', $userIds)->get();
            } else {
                return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một người dùng để gửi thông báo.');
            }
        } else {
            return redirect()->back()->with('error', 'Tùy chọn gửi thông báo không hợp lệ.');
        }

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => $message,
                'is_admin' => 1,
                'send_to_all' => $sendToAll ? 1 : 0,
                'send_to_group' => $sendToGroup ? 1 : 0,
            ]);
        }

        return redirect()->back()->with('success', 'Thông báo đã được gửi thành công.');
    }

    public function destroy($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            Log::error("Thông báo không tồn tại với ID: {$id}");
            return redirect()->back()->with('error', 'Thông báo không tồn tại!');
        }

        if ($notification->send_to_all) {
            $notification->delete();
            Log::info("Thông báo với ID: {$id} đã được xóa thành công.");
        } else {
            $user = $notification->user;

            if ($user) {
                Log::info("Thông báo với ID: {$id} đã được xóa thành công. Người nhận: {$user->name} ({$user->email})");
            } else {
                Log::info("Thông báo với ID: {$id} đã được xóa thành công. Không tìm thấy người nhận.");
            }

            $notification->delete();
        }

        return redirect()->back()->with('success', 'Thông báo đã được xóa thành công!');
    }
}
