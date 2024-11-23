@extends('layouts.student')

@section('content')
<div class="container mt-4">
    <!-- Marquee thông báo -->
    <div class="alert alert-primary marquee text-center shadow-sm">
        @if($upcoming_activities->isNotEmpty())
        <p>🎉 <strong>Lịch hoạt động mới</strong> đã được cập nhật. Xem ngay!</p>
        @else
        <p>🔥 <strong>Tham gia các hoạt động thú vị</strong> sắp tới!</p>
        @endif
    </div>

    <!-- Nút chuyển đổi form -->
    <button id="toggleFormBtn" class="btn btn-info mb-3 shadow">
        Gửi thắc mắc của bạn
    </button>

    <!-- Form gửi thắc mắc của sinh viên -->
    <div class="card shadow-sm" id="issueForm" style="display: none;">
        <div class="card-header bg-info text-white">Gửi thắc mắc của bạn:</div>
        <div class="card-body">
            <form action="{{ route('student.issues.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="message" class="form-label">Nội dung thắc mắc:</label>
                    <textarea class="form-control issue-textarea" id="message" name="message" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Gửi</button>
            </form>
            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>

    <!-- Hoạt động sắp tới -->
    <div class="row mt-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Hoạt động sắp tới</div>
                <div class="card-body">
                    @if($upcoming_activities->isEmpty())
                    <p>Không có hoạt động nào sắp tới.</p>
                    @else
                    @foreach ($upcoming_activities->take(3) as $activity)
                    <div class="activity mb-3">
                        <h5>
                            <a href="{{ route('activities.show', ['id' => $activity->id]) }}" class="text-decoration-none">
                                {{ $activity->name }}
                            </a>
                        </h5>
                        <p>Ngày: <strong>{{ $activity->date->format('d/m/Y') }}</strong></p>
                        <p>Địa điểm: <strong>{{ $activity->location }}</strong></p>
                        @if($activity->registration_start <= now() && $activity->registration_end >= now())
                        <a href="{{ route('registrations.create', ['id' => $activity->id]) }}" class="btn btn-outline-success btn-sm">Đăng ký</a>
                        @else
                        <button class="btn btn-outline-secondary btn-sm" disabled>
                            @if($activity->registration_start > now()) Chưa đến thời gian đăng ký
                            @else Hết hạn đăng ký
                            @endif
                        </button>
                        @endif
                    </div>
                    @endforeach
                    <a href="{{ route('activities.index') }}" class="btn btn-link">Xem tất cả hoạt động</a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thống kê hoạt động đã tham gia -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">Hoạt động đã tham gia</div>
                <div class="card-body">
                    @if(!empty($participated_activities))
                    <ul>
                        @foreach($participated_activities as $activity)
                        <li>{{ $activity }}</li>
                        @endforeach
                    </ul>
                    @else
                    <p>Bạn chưa tham gia hoạt động nào.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Thông báo mới -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">Thông báo mới</div>
                <div class="card-body">
                    @if(isset($notifications) && $notifications->isEmpty())
                    <div class="alert alert-info text-center">Không có thông báo nào mới.</div>
                    @else
                    @foreach ($notifications as $notification)
                    <a href="{{ route('student.issues.index') }}" class="text-decoration-none">
                        <div class="alert alert-info">{{ $notification['message'] }}</div>
                    </a>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* Button Styling */
    #toggleFormBtn {
        background-color: #17a2b8;
        padding: 8px 16px;
        border-radius: 5px;
        transition: background-color 0.3s;
        font-size: 14px;
    }

    #toggleFormBtn:hover {
        background-color: #138496;
    }

    /* Card Styling */
    .card {
        margin-bottom: 20px;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: scale(1.02);
    }

    /* Textarea Styling */
    .issue-textarea {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
        width: 100%;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    .issue-textarea:focus {
        border-color: #007bff;
        outline: none;
    }

    /* Submit Button Styling */
    .issue-submit-btn {
        background-color: #007bff;
        color: white;
        padding: 8px 12px;
        border-radius: 4px;
        width: 100%;
        border: none;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .issue-submit-btn:hover {
        background-color: #0056b3;
    }

    /* Success Alert Styling */
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 5px;
        border-radius: 4px;
        font-size: 12px;
    }

    /* Marquee Styling */
    .marquee {
        padding: 10px;
        background-color: #f0f8ff;
        border: 1px solid #007bff;
        border-radius: 5px;
        font-size: 18px;
        margin-bottom: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: scroll 25s linear infinite;
    }

    @keyframes scroll {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
</style>

@endsection
