@extends('layouts.admin') <!-- Kế thừa layout của admin -->

@section('content')
<div class="container mt-4">
    <h2>Xử lý vấn đề từ sinh viên</h2>

    <div class="card">
        <div class="card-header bg-warning text-white">
            Thông báo từ: <strong>{{ $issue->student_name }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Nội dung:</strong> {{ $issue->message }}</p>
            <p><small>Gửi vào ngày: {{ $issue->created_at->format('d/m/Y H:i') }}</small></p>
            <form action="{{ route('admin.issues.resolve', $issue->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success">Đánh dấu là đã xử lý</button>
            </form>
        </div>
    </div>

    <a href="{{ route('admin.issues.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách vấn đề</a>
</div>
@endsection
