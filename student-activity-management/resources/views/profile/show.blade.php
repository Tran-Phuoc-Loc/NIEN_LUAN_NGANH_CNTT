@extends('layouts.student')

@section('content')
<div class="container">
    <h2>Thông tin cá nhân</h2>

    <div class="card">
        <div class="card-header">
            Thông tin người dùng
        </div>
        <div class="card-body">
            <p><strong>Ảnh đại diện:</strong></p>
            @if($student && $student->avatar)
            <img src="{{ asset('storage/' . $student->avatar) }}" alt="Avatar" width="150">
            @else
            <span>Chưa có ảnh</span>
            @endif
            <p><strong>Tên:</strong> {{ $user->name }}</p>
            <p><strong>Mã đoàn viên:</strong> {{ $user->student_id }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>

            @if($student)
            <p><strong>Số điện thoại:</strong> {{ $student->phone }}</p>
            <p><strong>Ngày vào đoàn:</strong> {{ $student->joining_date }}</p>
            <p><strong>Nơi cấp thẻ đoàn:</strong> {{ $student->card_issuing_place }}</p>
            @else
            <p>Thông tin sinh viên không có.</p>
            @endif

            <p><strong>Các hoạt động đã tham gia:</strong></p>
            @if(!empty($participated_activities))
                    <ul class="list-unstyled">
                        @foreach($participated_activities as $activity)
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> {{ $activity }}</li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-center text-muted"><i class="bi bi-x-circle"></i> Bạn chưa tham gia hoạt động nào.</p>
                    @endif

            <!-- Nút cập nhật thông tin -->
            <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">Cập nhật thông tin</a>
        </div>
    </div>
</div>
@endsection