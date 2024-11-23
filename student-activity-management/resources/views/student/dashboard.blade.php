@extends('layouts.student')

@section('content')
<div class="container pt-1">
    <!-- Thông báo marquee -->
    <div class="alert marquee shadow-sm text-center rounded-3">
        @if($upcoming_activities->isNotEmpty())
        <p>🎉 <strong>Lịch hoạt động mới</strong> đã được cập nhật. Xem ngay!</p>
        @else
        <p>🔥 <strong>Tham gia các hoạt động thú vị</strong> sắp tới!</p>
        @endif
    </div>

    <!-- Carousel -->
    <div id="activityCarousel" class="carousel slide mb-4 shadow rounded-3 overflow-hidden" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($carousel_images as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100" style="max-height: 500px; object-fit: cover;" alt="{{ $image->description ?? 'Hình ảnh hoạt động' }}">
                <div class="carousel-caption bg-dark bg-opacity-50 p-3 rounded">
                    <h5 class="text-white">{{ $image->title ?? '' }}</h5>
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#activityCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#activityCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>


    <div class="row g-4">
        <!-- Hoạt động sắp tới -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4" style="overflow: hidden;">
                <div class="card-header text-white text-center" style="background: linear-gradient(90deg, #56ab2f, #a8e063);">
                    <i class="bi bi-calendar2-event"></i> Hoạt động sắp tới
                </div>
                <div class="card-body">
                    @php
                    // Lọc hoạt động có thời gian đăng ký hợp lệ
                    $validActivities = $upcoming_activities->filter(function($activity) {
                    return $activity->registration_end && \Carbon\Carbon::now()->lessThanOrEqualTo($activity->registration_end);
                    });
                    @endphp

                    @if($validActivities->isEmpty())
                    <p class="text-center text-muted"><i class="bi bi-info-circle"></i> Không có hoạt động nào sắp tới.</p>
                    @else
                    @foreach ($validActivities->take(3) as $activity)
                    <div class="activity-item mb-4">
                        <h6>
                            <a href="{{ route('activities.show', ['id' => $activity->id]) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $activity->name }}
                            </a>
                        </h6>
                        <p class="small text-muted">
                            <i class="bi bi-calendar-event"></i> {{ $activity->date->format('d/m/Y') }}
                            <br>
                            <i class="bi bi-geo-alt"></i> {{ $activity->location }}
                        </p>
                        @if (\Carbon\Carbon::now()->lessThanOrEqualTo($activity->registration_end))
                        <a href="{{ route('registrations.create', ['id' => $activity->id]) }}" class="btn btn-outline-success btn-sm shadow-sm">Đăng ký</a>
                        @else
                        <span class="text-danger small"><i class="bi bi-x-circle"></i> Hết hạn đăng ký</span>
                        @endif
                    </div>
                    @endforeach
                    <a href="{{ route('activities.index') }}" class="btn btn-link text-success d-block text-center mt-3">Xem tất cả</a>
                    @endif

                </div>
            </div>
        </div>


        <!-- Hoạt động đã tham gia -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-white text-center" style="background: linear-gradient(90deg, #36d1dc, #5b86e5);">
                    <i class="bi bi-check-circle"></i> Hoạt động đã tham gia
                </div>
                <div class="card-body">
                    @if(!empty($participated_activities))
                    <ul class="list-unstyled">
                        @foreach($participated_activities as $activity)
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> {{ $activity }}</li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-center text-muted"><i class="bi bi-x-circle"></i> Bạn chưa tham gia hoạt động nào.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thông báo -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-white text-center" style="background: linear-gradient(90deg, #f7971e, #ffd200);">
                    <i class="bi bi-bell"></i> Thông báo
                </div>
                <div class="card-body p-3" style="max-height: 300px; overflow-y: auto;">
                    @if(isset($notifications) && $notifications->isEmpty())
                    <p class="text-center text-muted"><i class="bi bi-info-circle"></i> Không có thông báo nào mới.</p>
                    @else
                    <ul class="list-unstyled">
                        @foreach ($notifications as $notification)
                        @php
                        // Kiểm tra nếu thông báo là mới (ví dụ, trong vòng 24 giờ) và chưa đọc
                        $hasNewNotifications = $notifications->filter(function($notification) {
                        // Sử dụng Carbon để kiểm tra thời gian tạo thông báo
                        return \Carbon\Carbon::parse($notification->created_at)->diffInHours(now()) < 24 && !$notification->is_read;
                            })->isNotEmpty();
                            @endphp

                            <li class="d-flex align-items-center mb-2">
                                <i class="bi bi-envelope {{ $notification->is_read ? 'text-muted' : 'text-primary' }} me-2"></i>
                                <a href="{{ route('student.issues.index') }}" class="text-decoration-none text-dark d-flex justify-content-between w-100">
                                    <!-- Giới hạn nội dung thông báo bằng limit() -->
                                    <span class="{{ $hasNewNotifications ? 'text-danger' : '' }}">
                                        {{ \Illuminate\Support\Str::limit($notification['message'], 10, '...') }}
                                    </span>
                                    <small class="text-muted ms-2" style="white-space: nowrap;">
                                        {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                                    </small>
                                </a>
                                <!-- Nút để đánh dấu thông báo là đã đọc -->
                                @if(!$notification->is_read)
                                <form action="{{ route('student.issues.markAsRead', $notification->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Đánh dấu là đã đọc</button>
                                </form>
                                @endif
                            </li>
                            @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>



    </div>
</div>

<!-- CSS -->
<style>
    .marquee {
        background-color: #f8f9fa;
        font-size: 18px;
        padding: 10px;
        border-left: 4px solid #007bff;
        border-radius: 5px;
    }

    .carousel .carousel-caption {
        background: rgba(0, 0, 0, 0.5);
        padding: 15px;
        border-radius: 10px;
    }

    .btn {
        transition: all 0.3s ease-in-out;
    }

    .btn:hover {
        transform: scale(1.05);
    }

    .card {
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transform: translateY(-5px);
    }

    .notification-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* Hiển thị tối đa 2 dòng */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        /* Để chữ có thể xuống dòng */
        max-width: 85%;
        /* Chiếm 85% không gian */
        font-size: 0.9rem;
        /* Điều chỉnh kích thước chữ nếu cần */
    }
</style>
@endsection