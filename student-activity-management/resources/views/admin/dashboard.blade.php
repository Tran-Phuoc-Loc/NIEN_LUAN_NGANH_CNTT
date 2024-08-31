@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h1>Admin Dashboard</h1>
    </div>
    <!-- Thêm form đăng xuất vào view -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="card-body">
        <p class="lead">Giao diện trang chủ</p>

        <!-- Biểu đồ -->
        <div class="row">
            <div class="col-md-12">
                <canvas id="registrationChart"></canvas>
            </div>
        </div>
    </div>
</div>

<canvas id="registrationChart" width="400" height="200"></canvas>



@endsection