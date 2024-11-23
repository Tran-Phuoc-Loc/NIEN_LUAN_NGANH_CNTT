@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Thông tin chi tiết sinh viên</h1>

    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <img src="{{ $student->avatar ? asset('storage/' . $student->avatar) : asset('storage/students/avatars/default_avatar.png') }}"
                    class="img-fluid rounded-start" alt="{{ $student->name }}">
            </div>

            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $student->name }}</h5>
                    <p class="card-text"><strong>Email:</strong> {{ $student->email }}</p>
                    <p class="card-text"><strong>Số điện thoại:</strong> {{ $student->phone }}</p>
                    <p class="card-text"><strong>Ngày vào đoàn:</strong> {{ $student->joining_date }}</p>
                    <p class="card-text"><strong>Nơi cấp thẻ đoàn:</strong> {{ $student->card_issuing_place }}</p>
                </div>
            </div>
        </div>
    </div>

    <h2>Danh sách hoạt động đã tham gia</h2>

    @if(count($participated_activities) > 0)
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên hoạt động</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Ngày tổ chức</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($participated_activities as $index => $activity)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $activity->activity_name }}</td>
                <td>{{ $activity->description }}</td>
                <td>
                    @if ($activity->is_hidden)
                        <span class="badge bg-warning">Ẩn</span>
                    @else
                        <span class="badge bg-success">Hiển thị</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($activity->activity_date)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-center text-muted"><i class="bi bi-x-circle"></i> Sinh viên này chưa tham gia hoạt động nào.</p>
    @endif

    <a href="{{ route('admin.students') }}" class="btn btn-secondary">Quay lại danh sách</a>

    @endsection