<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Quản lý Hoạt động Đoàn/Hội</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark ">
            <div class="container">
                <!-- Logo Thương Hiệu -->
                <a class="navbar-brand" href="#">Tham gia Hoạt động Đoàn/Hội</a>
                
                <!-- Nút toggle cho menu trên màn hình nhỏ -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Các liên kết điều hướng -->
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <!-- Trang chủ -->
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('student.dashboard') }}">Trang chủ</a>
                        </li>
                        <!-- Hoạt động -->
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('activities.index') }}">Hoạt động</a>
                        </li>
                        <!-- Thông báo -->
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('student.issues.index') }}">Thông báo</a>
                        </li>
                        <!-- Tin tức -->
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('student.news.index') }}">Tin Tức</a>
                        </li>
                        <!-- Kiểm tra xem người dùng đã đăng nhập chưa -->
                        @auth
                        <!-- Thông tin cá nhân -->
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('profile.show') }}">Thông tin cá nhân</a>
                        </li>
                        <!-- Đăng xuất -->
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </li>
                        <!-- Form đăng xuất ẩn -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container">
        @yield('content')
    </div>

    <script>
        $(document).ready(function() {
            $('#toggleFormBtn').click(function() {
                $('#issueForm').slideToggle(); // Bật/tắt hiển thị biểu mẫu với hiệu ứng trượt
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>