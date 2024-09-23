@extends('layouts.student')

@section('content')
<div class="container">
    <div class="marquee">
        @if($upcoming_activities->isNotEmpty())
        <p>Chúng tôi đã cập nhật lịch hoạt động mới! Xem ngay!</p>
        @else
        <p>Hãy tham gia các hoạt động thú vị sắp tới!</p>
        @endif
    </div>
    <h2>Xin chào, {{ $user->name }}!</h2>

    <div class="grid">
        <div class="card">
            <div class="card-header">Hoạt động sắp tới</div>
            <div class="card-content">
                @if($upcoming_activities->isEmpty())
                <p>Không có hoạt động nào sắp tới.</p>
                @else
                @foreach ($upcoming_activities->sortBy('date')->take(3) as $activity) <!-- Lấy 3 hoạt động mới nhất -->
                <div class="activity">
                    <h3>
                        <a href="{{ route('activities.show', ['id' => $activity->id]) }}">
                            {{ $activity->name }}
                        </a>
                    </h3>
                    <p>Ngày diễn ra: <strong>{{ $activity->date->format('d/m/Y') }}</strong></p>
                    <p>Địa điểm: <strong>{{ $activity->location }}</strong></p>
                    <p>
                        <strong>Thời gian đăng ký:</strong>
                        {{ $activity->registration_start->format('d/m/Y') }}
                        đến
                        {{ $activity->registration_end->format('d/m/Y') }}
                    </p>
                    @if($activity->registration_start <= now() && $activity->registration_end >= now())
                        <a href="{{ route('registrations.create', ['id' => $activity->id]) }}" class="btn">Đăng ký tham gia</a>
                        @else
                        <button class="btn btn-secondary" disabled>
                            @if($activity->registration_start > now())
                            Chưa đến thời gian đăng ký
                            @else
                            Hết hạn đăng ký
                            @endif
                        </button>
                        @endif
                </div>
                @endforeach
                <p><a href="{{ route('activities.index') }}">Xem tất cả hoạt động</a></p> <!-- Liên kết đến trang hoạt động -->
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">Thống kê hoạt động mà bạn đã tham gia</div>
            <div class="card-content">
                <!-- Thông tin thống kê sẽ được thêm vào đây -->
            </div>
        </div>

        <div class="card">
            <div class="card-header">Thông báo mới</div>
            <div class="card-content">
                @foreach ($notifications as $notification)
                <div class="notification {{ $notification['type'] }}">
                    <p>{{ $notification['content'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection