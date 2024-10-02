@extends('layouts.admin')

@section('content')
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
@endsection
