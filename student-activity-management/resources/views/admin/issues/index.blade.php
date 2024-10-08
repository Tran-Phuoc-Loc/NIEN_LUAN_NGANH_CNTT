@extends('layouts.admin')

@section('content')

<!-- Thông báo từ sinh viên -->
<div class="card">
    <div class="card-header bg-warning text-white text-center">
        Thông báo từ sinh viên
    </div>
    <div class="card-content p-4">
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
    <div class="card-content p-4">
        @if ($adminNotifications->count() > 0)
        <ul class="list-group">
            @foreach ($adminNotifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>Thông báo:</strong> {{ $notification->message }}
                    <br><small>Gửi vào ngày: {{ $notification->created_at->format('d/m/Y H:i') }}</
                            @if ($notification->total > 1)
                        <br><small>Số lượng người nhận: {{ $notification->total }}</small>
                        @endif
                </div>
                <!-- Nút Xóa -->
                <form action="{{ route('admin.issues.destroy', $notification->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa thông báo này?')">Xóa</button>
                </form>
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



    @endsection