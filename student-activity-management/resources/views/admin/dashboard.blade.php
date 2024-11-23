@extends('layouts.admin')

@section('content')

<div class="container">
    <h2 class="mb-4">Chào mừng, Admin!</h2>

    <div class="grid">
        <div class="row">
            <!-- Thống kê tổng quan -->
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
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
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
        <!-- Biểu đồ hoạt động không dùng card -->
        <div class="col-md-9 mb-4">
            <div class="bg-secondary text-white text-center p-2 mb-2">
                <h5>Biểu đồ hoạt động</h5>
            </div>
            <div class="p-4">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Hoạt động gần đây -->
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
        var activityLabels = JSON.parse('{!! $activityLabelsJson !!}');
        var activityData = JSON.parse('{!! $activityDataJson !!}');

        // Lấy context của canvas cho biểu đồ
        var ctx = $('#activityChart');

        // Kiểm tra nếu phần tử canvas tồn tại trước khi tạo biểu đồ
        if (ctx.length) {
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
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top', // Vị trí của chú thích biểu đồ
                        },
                        title: {
                            display: true,
                            text: 'Số người tham gia theo hoạt động'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    var total = activityData.reduce((a, b) => a + b, 0);
                                    var currentValue = tooltipItem.raw;
                                    var percentage = Math.floor((currentValue / total) * 100); // Tính phần trăm
                                    return `${tooltipItem.label}: ${currentValue} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

@endsection