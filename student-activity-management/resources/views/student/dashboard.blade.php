@extends('layouts.student')

@section('content')
<div class="container">
    <!-- Marquee thông báo -->
    <div class="marquee">
        @if($upcoming_activities->isNotEmpty())
        <p>Chúng tôi đã cập nhật lịch hoạt động mới! Xem ngay!</p>
        @else
        <p>Hãy tham gia các hoạt động thú vị sắp tới!</p>
        @endif
    </div>

    <!-- Nút chuyển đổi form -->
    <button id="toggleFormBtn" class="btn btn-info mb-3">
        Gửi thắc mắc của bạn
    </button>

    <!-- Form gửi thắc mắc của sinh viên (Initially hidden) -->
    <div class="card" id="issueForm" style="display: none;">
        <div class="card-header">Gửi thắc mắc của bạn:</div>
        <div class="card-content">
            <form action="{{ route('student.issues.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="message" class="form-label">Nội dung thắc mắc:</label>
                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
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

    <!-- Lời chào -->
    <h2>Xin chào, {{ $user->name }}!</h2>

    <div class="grid">
        <!-- Hoạt động sắp tới -->
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

        <!-- Thống kê hoạt động -->
        <div class="card">
            <div class="card-header">Thống kê hoạt động mà bạn đã tham gia</div>
            <div class="card-content">
                <!-- Thông tin thống kê sẽ được thêm vào đây -->
            </div>
        </div>

        <!-- Thông báo mới -->
        <div class="card ">
            <div class="card-header">
                <h4>Thông báo mới</h4>
            </div>
            <div class="card-body">
                @if (isset($notifications) && $notifications->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Không có thông báo nào mới.
                </div>
                @elseif (isset($notifications))
                @foreach ($notifications as $notification)
                <div class="alert 
                    @if($notification['type'] == 'success') alert-success 
                    @elseif($notification['type'] == 'error') alert-danger 
                    @elseif($notification['type'] == 'warning') alert-warning 
                    @else alert-info 
                    @endif">
                    <strong>Admin thông báo:</strong>
                    <i class="
                    @if($notification['type'] == 'success') fas fa-check-circle 
                    @elseif($notification['type'] == 'error') fas fa-exclamation-circle 
                    @elseif($notification['type'] == 'warning') fas fa-exclamation-triangle 
                    @else fas fa-info-circle 
                    @endif"></i>
                    {{ $notification['message'] }}
                </div>
                @endforeach
                @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Không có thông báo nào mới.
                </div>
                @endif
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
    .issue-form-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .issue-form-header {
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        padding: 8px;
        border-radius: 5px;
        margin-bottom: 10px;
        text-align: center;
    }

    /* Form Content Styling */
    .issue-form-content {
        padding: 0;
    }

    .form-group label {
        font-weight: 600;
        font-size: 12px;
        color: #333;
    }

    /* Textarea Styling */
    .issue-textarea {
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
        width: 100%;
        font-size: 12px;
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
</style>
@endsection