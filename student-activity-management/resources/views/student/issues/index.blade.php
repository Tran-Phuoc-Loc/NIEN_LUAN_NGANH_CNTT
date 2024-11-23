@extends('layouts.student')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">📢 Thông báo</h2>

    @if (isset($notifications) && $notifications->isEmpty())
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i> Không có thông báo nào mới.
    </div>
    @elseif (isset($notifications))
    @foreach ($notifications as $notification)
    <div class="card mb-4 notification-card {{ $notification->is_read ? 'read' : 'unread' }} shadow-sm border-left-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title d-flex align-items-center">
                    <i class=" 
                        @if($notification->type == 'success') fas fa-check-circle text-success
                        @elseif($notification->type == 'error') fas fa-exclamation-circle text-danger
                        @elseif($notification->type == 'warning') fas fa-exclamation-triangle text-warning
                        @else fas fa-info-circle text-info
                        @endif"></i>
                    <span class="ml-2">{{ ucfirst($notification->type) }} từ Admin</span>
                </h5>
                <span class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <hr>
            <p class="card-text">{{ $notification->message }}</p>

            <!-- Nút để đánh dấu là đã đọc -->
            @if(!$notification->is_read)
            <form action="{{ route('student.issues.markAsRead', $notification->id) }}" method="POST" class="d-inline-block">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">Đánh dấu là đã đọc</button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
    @else
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i> Không có thông báo nào mới.
    </div>
    @endif
</div>

<!-- Styles -->
<style>
    /* Đặt khung và hiệu ứng */
    .notification-card {
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    /* Màu sắc theo loại thông báo */
    .notification-card .fa-check-circle {
        border-left: 4px solid #28a745;
    }

    .notification-card .fa-exclamation-circle {
        border-left: 4px solid #dc3545;
    }

    .notification-card .fa-exclamation-triangle {
        border-left: 4px solid #ffc107;
    }

    .notification-card .fa-info-circle {
        border-left: 4px solid #17a2b8;
    }

    .notification-card:hover {
        transform: scale(1.02);
        background-color: #f8f9fa;
    }

    /* Định dạng tiêu đề */
    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
    }

    /* Căn chỉnh nội dung */
    .card-text {
        font-size: 1rem;
        margin-top: 1rem;
    }

    /* Hiển thị biểu tượng và khoảng cách */
    .card-title i {
        font-size: 1.5rem;
        margin-right: 10px;
    }

    .ml-2 {
        margin-left: 10px;
    }
</style>
@endsection