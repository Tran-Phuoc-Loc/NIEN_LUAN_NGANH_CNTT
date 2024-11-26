@extends('layouts.admin')

@section('content')

<div class="container">
    <!-- Chào mừng -->
    <h2 class="mb-4">Chào mừng, Admin!</h2> 
        <!-- Button kích hoạt modal -->
        <div class="text-end mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarouselModal">
            Thêm ảnh vào trang người dùng
        </button>
    </div>
        
    @if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
    @endif

    <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 text-center hover-card">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h6 class="card-title">Tổng số người dùng</h6>
                    <h2>{{ $totalMembers }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 text-center hover-card">
                <div class="card-body">
                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                    <h6 class="card-title">Tổng số hoạt động</h6>
                    <h2>{{ $totalActivities }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 text-center hover-card">
                <div class="card-body">
                    <i class="fas fa-check fa-2x text-primary mb-2"></i>
                    <h6 class="card-title">Hoạt Động Hiện Có</h6>
                    <h2>{{ $visibleActivitiesCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card shadow-sm border-0 text-center hover-card">
                <div class="card-body">
                    <i class="fas fa-check fa-2x text-primary mb-2"></i>
                    <h6 class="card-title">Tổng số Tin tức</h6>
                    <h2>{{ $visibleActivitiesCount }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông báo từ sinh viên và biểu đồ -->
    <div class="row mb-4">
        <!-- Thông báo từ sinh viên -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-header bg-warning text-white">
                    <h5>Thông báo từ sinh viên</h5>
                </div>
                <div class="card-body p-3">
                    @if ($studentIssues->isEmpty())
                    <p>Không có thông báo từ sinh viên.</p>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach ($studentIssues as $issue)
                        <li class="list-group-item d-flex justify-content-between align-items-center
                                            @if($issue->is_resolved) bg-success text-white @endif">
                            <div>
                                <strong>{{ $issue->student_name }}:</strong> {{ $issue->message }}
                                <br><small>Gửi ngày: {{ $issue->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            @if(!$issue->is_resolved)
                            <a href="{{ route('admin.issues.resolve', $issue->id) }}" class="btn btn-sm btn-warning">Xử lý</a>
                            @else
                            <span class="badge bg-success">Đã xử lý</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <a href="{{ route('admin.issues.index') }}" class="btn btn-warning w-100 mt-3">Xem tất cả thông báo</a>
                </div>
            </div>
        </div>

        <!-- Biểu đồ hoạt động -->
        <div class="col-md-9">
            <div class="bg-secondary text-white text-center p-2 mb-2">
                <h5>Biểu đồ hoạt động</h5>
            </div>
            <div class="p-4">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Hoạt động gần đây -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white text-center">
                    Hoạt động gần đây
                </div>
                <div class="card-content p-4">
                    @foreach($recentActivities as $activity)
                    <div class="activity-item mb-3">
                        <h5>{{ $activity->name }}</h5>
                        <p>Ngày diễn ra: {{ $activity->date->format('d/m/Y') }}</p>
                        <p>Số người tham gia: {{ $activity->registrations_count }}</p>
                    </div>
                    @endforeach
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-primary w-100">Xem tất cả hoạt động</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thêm ảnh vào carousel -->
    <div class="modal fade" id="addCarouselModal" tabindex="-1" aria-labelledby="addCarouselLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addCarouselLabel">Thêm ảnh vào carousel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.carousel.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="images" class="form-label">Chọn ảnh:</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple required>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề (tùy chọn):</label>
                            <input type="text" name="title" id="title" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả (tùy chọn):</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Tải lên</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@if(isset($activityLabels) && isset($activityData))
@php
$activityLabelsJson = json_encode($activityLabels);
$activityDataJson = json_encode($activityData);
@endphp
@else
@php
$activityLabelsJson = json_encode([]); // Mảng rỗng
$activityDataJson = json_encode([]); // Mảng rỗng
@endphp
@endif

<script>
    $(document).ready(function() {
        // Biến dữ liệu biểu đồ hoạt động
        var activityLabels = JSON.parse(@json($activityLabelsJson));
        var activityData = JSON.parse('{!! $activityDataJson !!}');
        console.log('Raw activityLabelsJson:', '{!! $activityLabelsJson !!}');


        // Lấy context của canvas cho biểu đồ
    var ctx = $('#activityChart');

// Kiểm tra nếu phần tử canvas tồn tại và dữ liệu hợp lệ
if (ctx.length && activityLabels.length && activityData.length) {
    try {
        var totalParticipants = activityData.reduce((a, b) => a + b, 0); // Tính tổng số người tham gia

        // Khởi tạo biểu đồ
        var activityChart = new Chart(ctx, {
            type: 'bar', // Loại biểu đồ
            data: {
                labels: activityLabels, // Sử dụng biến JavaScript đã được truyền
                datasets: [{
                    label: 'Số người tham gia',
                    data: activityData, // Sử dụng biến JavaScript đã được truyền
                    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Màu nền
                    borderColor: 'rgba(54, 162, 235, 1)', // Màu viền
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượng' // Tiêu đề trục Y
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Hoạt động' // Tiêu đề trục X
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top', // Vị trí của chú thích biểu đồ
                    },
                    title: {
                        display: true,
                        text: 'Số người tham gia theo hoạt động' // Tiêu đề biểu đồ
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                // Lấy giá trị hiện tại và tính phần trăm
                                var currentValue = tooltipItem.raw;
                                var percentage = totalParticipants
                                    ? ((currentValue / totalParticipants) * 100).toFixed(2) // Phần trăm 2 chữ số thập phân
                                    : 0;
                                return `${tooltipItem.label}: ${currentValue} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Lỗi khi tạo biểu đồ:', error.message);
    }
} else {
    console.warn('Dữ liệu hoặc canvas không hợp lệ.');
}

    });
</script>

@endsection