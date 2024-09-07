<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Link Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Link CSS -->
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f4f7fc;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    header {
        background: #1e88e5;
        color: #fff;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
        font-size: 16px;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    /* Card Styles */
    .card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        background: #1e88e5;
        color: white;
        padding: 20px;
        font-size: 18px;
        font-weight: bold;
        border-bottom: 1px solid #e0e0e0;
        text-align: center;
    }

    .card-content {
        padding: 20px;
    }

    /* Statistic Grid */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        text-align: center;
    }

    .stat-item {
        background: #e3f2fd;
        padding: 20px;
        border-radius: 10px;
        transition: background 0.3s ease;
    }

    .stat-item:hover {
        background: #bbdefb;
    }

    .stat-number {
        font-size: 28px;
        font-weight: bold;
        color: #1e88e5;
    }

    .activity-item {
        border-bottom: 1px solid #e0e0e0;
        padding: 15px 0;
        transition: padding-left 0.3s ease;
    }

    .activity-item:hover {
        padding-left: 10px;
        background-color: #e3f2fd;
        border-radius: 5px;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .btn {
        display: inline-block;
        background: #1e88e5;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
        transition: background 0.3s;
        text-align: center;
        margin-top: 10px;
    }

    .btn:hover {
        background: #1565c0;
    }

    .chart-container {
        text-align: center;
    }

    .chart-container p {
        margin-top: 10px;
        font-size: 14px;
        color: #666;
    }

    .navbar-brand {
        font-weight: bold;
        font-size: 1.5rem;
    }

    .card-header {
        background-color: #007bff;
        color: white;
    }

    .card-body {
        background-color: #f8f9fa;
    }

    .card-title {
        font-size: 1.25rem;
    }

    .card-text {
        font-size: 1rem;
    }
</style>

<body>
    <div class="admin-wrapper">
        <header class="bg-dark text-white p-3">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <!-- Chỉ admin mới thấy được -->
                            @if(Auth::check() && Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.students') }}">Quản lý sinh viên</a>
                            </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('activities.index') }}">Quản lý Hoạt động</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('registrations.index') }}">Quản lý Thành viên</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('registrations.index') }}">Báo cáo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            </li>
                            <!-- Thêm form đăng xuất vào view -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>