<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Quản lý Hoạt động Đoàn/Hội</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand fw-bold" href="#">Hoạt động Đoàn/Hội</a>

                <!-- Nút toggle cho màn hình nhỏ -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Liên kết điều hướng -->
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <!-- Trang chủ -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">Trang chủ</a>
                        </li>
                        <!-- Hoạt động -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('activities.index') ? 'active' : '' }}" href="{{ route('activities.index') }}">Hoạt động</a>
                        </li>
                        <!-- Hoạt động -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('posts.index') ? 'active' : '' }}" href="{{ route('posts.index') }}">Bài viết</a>
                        </li>

                        <!-- Thông báo -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('student.issues.index') ? 'active' : '' }}"
                                href="{{ route('student.issues.index') }}">
                                Thông báo
                            </a>
                        </li>

                        <!-- Tin tức -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('student.news.index') ? 'active' : '' }}" href="{{ route('student.news.index') }}">Tin Tức</a>
                        </li>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav" style="display: flex; align-items: center;">

                                @if(auth()->check())
                                <li class="nav-item dropdown ms-3">
                                    <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="user-circle">
                                            @if(auth()->check() && auth()->user()->student && auth()->user()->student->avatar)
                                            <img src="{{ asset('storage/' . auth()->user()->student->avatar) }}" alt="Ảnh đại diện" class="img-fluid" style="border-radius: 50%; width: 45px; height: 45px;" loading="lazy">
                                            @else
                                            <img src="{{ asset('storage/students/avatars/default_avatar.png') }}" alt="Ảnh mặc định" class="img-fluid" style="border-radius: 50%; width: 45px; height: 45px;" loading="lazy">
                                            @endif

                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">Thông tin</a></li>
                                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                                @else

                                <li class="nav-item ms-3" style="text-align: right;">
                                    <a class="nav-link" href="{{ route('login') }}">Đăng Nhập</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- Nút mở form -->
    <button id="toggleFormBtn" class="btn btn-primary" style="position: fixed; bottom: 30px; right: 20px; z-index: 1000; width: 70px; height: 70px; font-size: 1.5rem; display: flex; justify-content: center; align-items: center; border-radius: 50%;">
        <i class="bi bi-chat-right-dots"></i>
    </button>

    <!-- Form -->
    <div id="issueForm" class="card shadow-sm p-4" style="display: none; position: fixed; bottom: 100px; right: 50px; z-index: 1000; width: 300px;">
        <h5 class="card-title mb-3">Gửi thắc mắc của bạn</h5>
        <form action="{{ route('student.issues.store') }}" method="POST">
            @csrf
            <textarea class="form-control mb-3" id="message" name="message" rows="3" placeholder="Nhập nội dung thắc mắc của bạn" required></textarea>
            <button type="submit" class="btn btn-success w-100">Gửi</button>
        </form>
        @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
        @endif
    </div>

    <div class="container">
        @yield('content')
    </div>
    <footer class="bg-dark text-light">
        <div class="container">
            <div class="row">
                <!-- Cột 1: Thông tin liên hệ -->
                <div class="col-lg-4 mb-3">
                    <h5 class="text-uppercase">Liên hệ</h5>
                    <p class="mb-1"><i class="bi bi-telephone-fill"></i> Điện thoại: 038 531 5971</p>
                    <p><i class="bi bi-envelope-fill"></i> Email: ttp6889@gmail.com</p>
                </div>

                <!-- Cột 2: Liên kết nhanh -->
                <div class="col-lg-4 mb-3">
                    <h5 class="text-uppercase">Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-decoration-none text-light">Trang chủ</a></li>
                        <li><a href="{{ route('activities.index') }}" class="text-decoration-none text-light">Danh sách hoạt động</a></li>
                        <li><a href="{{ route('profile.show') }}" class="text-decoration-none text-light">Hồ sơ cá nhân</a></li>
                        <li><a href="" class="text-decoration-none text-light">Liên hệ</a></li>
                    </ul>
                </div>

                <!-- Cột 3: Thông tin bản quyền -->
                <div class="col-lg-4 mb-3 text-center text-lg-start">
                    <h5 class="text-uppercase">Bản quyền</h5>
                    <p>&copy; {{ date('Y') }} Quản lý hoạt động Đoàn & Hội Thao.</p>
                    <p>Tất cả các quyền được bảo lưu.</p>
                    <div>
                        <!-- Social Media Links -->
                        <a href="#" class="text-light me-2"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-2"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light me-2"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const issueForm = document.getElementById('issueForm');

            toggleFormBtn.addEventListener('click', function() {
                if (issueForm.style.display === 'none' || issueForm.style.display === '') {
                    issueForm.style.display = 'block'; // Hiển thị form
                } else {
                    issueForm.style.display = 'none'; // Ẩn form
                }
            });
        });
    </script>
</body>

</html>