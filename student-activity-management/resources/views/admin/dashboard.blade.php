@extends('layouts.admin')

@section('content')
<div class="container">
        <h2>Chào mừng, Admin!</h2>
        
        <div class="grid">
            <div class="card">
                <div class="card-header">Thống kê tổng quan</div>
                <div class="card-content">
                    <div class="stat-grid">
                        <div class="stat-item">
                            <div class="stat-number"></div>
                            <div>Tổng số thành viên</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"></div>
                            <div>Tổng số hoạt động</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"></div>
                            <div>Chờ phê duyệt</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">Hoạt động gần đây</div>
                <div class="card-content">

                        <div class="activity-item">
                            <h3></h3>
                            <p>Ngày: </p>
                            <p>Số người tham gia:</p>
                        </div>

                    <a href="#" class="btn">Xem tất cả hoạt động</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">Biểu đồ tăng trưởng thành viên</div>
                <div class="card-content">
                    <p>Số lượng thành viên trong 6 tháng gần đây</p>
                </div>
            </div>
        </div>
    </div>



@endsection