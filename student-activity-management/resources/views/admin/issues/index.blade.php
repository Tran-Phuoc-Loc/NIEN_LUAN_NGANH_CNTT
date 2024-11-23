@extends('layouts.admin')

@section('content')

<!-- Thông báo từ sinh viên -->
<div class="card">
    <div class="card-header bg-warning text-white text-center">
        Thông báo từ sinh viên
    </div>
    <div class="card-body p-4">
        @if ($studentIssues->count() > 0)
        <ul class="list-group">
            @foreach ($studentIssues as $issue)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $issue->student_name }}:</strong> {{ $issue->message }}
                    <br><small>Gửi vào ngày: {{ $issue->created_at->format('d/m/Y H:i') }}</small>
                </div>
                @if (!$issue->is_resolved)
                <a href="{{ route('admin.issues.resolve', $issue->id) }}" class="btn btn-sm btn-warning">Xử lý</a>
                @else
                <span class="badge bg-success">Đã xử lý</span>
                @endif
            </li>
            @endforeach
        </ul>
        <div class="mt-3">
            {{ $studentIssues->links() }} <!-- Pagination links -->
        </div>
        @else
        <p class="text-center">Không có vấn đề nào từ sinh viên.</p>
        @endif
    </div>
</div>

<!-- Thông báo từ admin -->
<div class="card mt-4">
    <div class="card-header bg-info text-white text-center">
        Thông báo từ Admin
    </div>
    <div class="card-body p-4">
        @if ($adminNotifications->count() > 0)
        <ul class="list-group">
            @foreach ($adminNotifications as $notification)
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Thông báo:</strong> {{ $notification->message }}
                        <br><small>Gửi vào ngày: {{ $notification->created_at->format('d/m/Y H:i') }}</small>
                        <strong>Danh sách sinh viên nhận:</strong> {{ $notification->students->count() }} sinh viên nhận thông báo
                    </div>
                    <div>
                        @if ($notification->students->isNotEmpty())
                        <button class="btn btn-link" data-bs-toggle="modal" data-bs-target="#studentListModal" data-notification-id="{{ $notification->id }}">Xem danh sách</button>
                        @endif
                        <form action="{{ route('admin.issues.destroy', $notification->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa thông báo này?')">Xóa</button>
                        </form>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        <div class="mt-3">
            {{ $adminNotifications->links() }} <!-- Pagination links -->
        </div>
        @else
        <p class="text-center">Không có thông báo từ Admin.</p>
        @endif
    </div>
</div>

<!-- Vòng lặp hiển thị thông báo từ Admin -->
@foreach ($adminNotifications as $notification)
    <!-- Modal cho danh sách sinh viên -->
    <div class="modal fade" id="studentListModal{{ $notification->id }}" tabindex="-1" aria-labelledby="studentListModalLabel{{ $notification->id }}" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentListModalLabel{{ $notification->id }}">Danh sách sinh viên nhận thông báo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="student-list" id="studentList{{ $notification->id }}">
                        <!-- Danh sách sinh viên sẽ được tạo động trong JavaScript -->
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