@extends('layouts.student')

@section('content')
    <div class="container">
        <h2>Xin chào, {{ $user->name }}!</h2>

        <div class="grid">
            <div class="card">
                <div class="card-header">Hoạt động sắp tới</div>
                <div class="card-content">
                    @foreach ($upcoming_activities as $activity)
                    <div class="activity">
                        <h3>{{ $activity['name'] }}</h3>
                        <p>Ngày: {{ $activity['date'] }}</p>
                        <p>Địa điểm: {{ $activity['location'] }}</p>
                        <a href="#" class="btn">Đăng ký tham gia</a>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div class="card-header">Thống kê hoạt động mà bạn đã tham gia</div>
                <div class="card-content">

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