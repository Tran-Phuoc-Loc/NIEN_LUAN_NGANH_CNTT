<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Quản lý Hoạt động Đoàn/Hội</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        header {
            background: #1a237e;
            color: #fff;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: #3f51b5;
            color: white;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        .card-content {
            padding: 20px;
        }

        .activity,
        .notification {
            background: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .btn {
            display: inline-block;
            background: #3f51b5;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #283593;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            height: 200px;
            padding: 10px 0;
        }

        .bar-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .bar {
            width: 30px;
            background-color: #3f51b5;
            margin: 0 5px;
        }

        .bar-label {
            margin-top: 5px;
            font-size: 12px;
        }

        .notification.info {
            border-left: 4px solid #2196F3;
        }

        .notification.success {
            border-left: 4px solid #4CAF50;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <h1>Quản lý Hoạt động Đoàn/Hội</h1>
            <ul>
                <li><a href="{{ route('student.dashboard') }}">Trang chủ</a></li>
                <li><a href="{{ route('activities.index') }}">Hoạt động</a></li>
                <li><a href="#">Thông báo</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </li>
                <!-- Thêm form đăng xuất vào view -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>
        </nav>
    </header>

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
                <div class="card-header">Thống kê hoạt động</div>
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
</body>

</html>