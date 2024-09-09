@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Chào mừng, Admin!</h2>
    
    <div class="grid">
        <!-- Thống kê tổng quan -->
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                Thống kê tổng quan
            </div>
            <div class="card-content p-4">
                <div class="stat-grid d-flex justify-content-around">
                    <div class="stat-item text-center">
                        <div class="stat-number display-4 text-primary">150</div>
                        <div>Tổng số thành viên</div>
                    </div>
                    <div class="stat-item text-center">
                        <div class="stat-number display-4 text-primary">25</div>
                        <div>Tổng số hoạt động</div>
                    </div>
                    <div class="stat-item text-center">
                        <div class="stat-number display-4 text-primary">5</div>
                        <div>Chờ phê duyệt</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hoạt động gần đây -->
        <div class="card">
            <div class="card-header bg-info text-white text-center">
                Hoạt động gần đây
            </div>
            <div class="card-content p-4">
                <div class="activity-item mb-3">
                    <h5>Sự kiện từ thiện</h5>
                    <p>Ngày: 10/09/2024</p>
                    <p>Số người tham gia: 120</p>
                </div>
                <div class="activity-item mb-3">
                    <h5>Hoạt động thể thao</h5>
                    <p>Ngày: 02/09/2024</p>
                    <p>Số người tham gia: 80</p>
                </div>
                <a href="{{ route('activities.index') }}" class="btn btn-primary w-100">Xem tất cả hoạt động</a>
            </div>
        </div>
        
        <!-- Biểu đồ tăng trưởng thành viên -->
        <div class="card">
            <div class="card-header bg-success text-white text-center">
                Biểu đồ tăng trưởng thành viên
            </div>
            <div class="card-content p-4">
                <canvas id="memberGrowthChart"></canvas>
                <p class="text-center mt-3">Số lượng thành viên trong 6 tháng gần đây</p>
            </div>
        </div>
    </div>
</div>

<!-- Thêm đoạn script cho biểu đồ -->
<script>
    var ctx = document.getElementById('memberGrowthChart').getContext('2d');
    var memberGrowthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8'],
            datasets: [{
                label: 'Số lượng thành viên',
                data: [50, 100, 150, 200, 250, 300],
                backgroundColor: 'rgba(30, 136, 229, 0.2)',
                borderColor: 'rgba(30, 136, 229, 1)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
