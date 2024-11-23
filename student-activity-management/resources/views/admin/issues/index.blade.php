@extends('layouts.admin')

@section('content')

<div class="container mt-4">
    <!-- Thông báo từ sinh viên -->
    <div class="card shadow rounded mb-4">
        <div class="card-header bg-warning text-white text-center">
            <h5><i class="bi bi-exclamation-circle-fill me-2"></i>Thông báo từ Sinh viên</h5>
        </div>
        <div class="card-body">
            @if ($studentIssues->count() > 0)
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-warning">
                    <tr>
                        <th>Sinh viên</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studentIssues as $issue)
                    <tr>
                        <td><strong>{{ $issue->student_name }}</strong></td>
                        <td>{{ $issue->message }}</td>
                        <td>{{ $issue->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if (!$issue->is_resolved)
                            <span class="badge bg-warning">Chưa xử lý</span>
                            @else
                            <span class="badge bg-success">Đã xử lý</span>
                            @endif
                        </td>
                        <td>
                            @if (!$issue->is_resolved)
                            <a href="{{ route('admin.issues.resolve', $issue->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-check2-circle"></i> Xử lý
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $studentIssues->links() }} <!-- Pagination links -->
            </div>
            @else
            <p class="text-center text-muted"><i class="bi bi-info-circle"></i> Không có vấn đề nào từ sinh viên.</p>
            @endif
        </div>
    </div>

    <!-- Thông báo từ Admin -->
    <div class="card shadow rounded">
        <div class="card-header bg-info text-white text-center">
            <h5><i class="bi bi-bell-fill me-2"></i>Thông báo từ Admin</h5>
        </div>
        <div class="card-body">
            @if ($adminNotifications->count() > 0)
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-info">
                    <tr class="text-center">
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th>Số sinh viên nhận</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adminNotifications as $notification)
                    <tr>
                        <td>{{ $notification->message }}</td>
                        <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">{{ $notification->students->count() }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Xem danh sách sinh viên -->
                                <button
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#studentListModal{{ $notification->id }}">
                                    <i class="bi bi-people-fill me-1"></i>Danh sách
                                </button>

                                <!-- Xóa thông báo -->
                                <form
                                    action="{{ route('admin.issues.destroy', $notification->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa thông báo này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash3-fill me-1"></i>Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $adminNotifications->links() }} <!-- Pagination links -->
            </div>
            @else
            <p class="text-center text-muted">
                <i class="bi bi-info-circle"></i> Không có thông báo từ Admin.
            </p>
            @endif
        </div>
        @if (auth()->user()->is_super_admin)
        <div class="card-footer text-end">
            <form
                action="{{ route('admin.notifications.destroyAll') }}"
                method="POST"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa tất cả thông báo gửi tới tất cả người dùng?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">
                    <i class="bi bi-trash3-fill me-1"></i> Xóa tất cả thông báo
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- Modal cho danh sách sinh viên -->
@foreach ($adminNotifications as $notification)
<div class="modal fade" id="studentListModal{{ $notification->id }}" tabindex="-1" aria-labelledby="studentListModalLabel{{ $notification->id }}" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentListModalLabel{{ $notification->id }}">Danh sách sinh viên nhận thông báo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach ($notification->students as $student)
                    <li class="list-group-item">{{ $student->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endforeach

@php
// Lấy danh sách sinh viên cho notificationId
$studentListData = $adminNotifications->keyBy('id')->map(function($notification) {
return [
'students' => $notification->students->map(function($student) {
return [
'name' => $student->name,
'email' => $student->email
];
})->toArray()
];
})->toArray();
@endphp
@endsection