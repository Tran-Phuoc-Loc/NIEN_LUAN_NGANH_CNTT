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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Link CSS -->
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<style>
    body {
        background-color: #f4f7fc;
    }

    .container {
        margin: 40px auto;
        padding: 0 20px;
    }

    header {
        background: #1e88e5;
    }

    nav ul li a {
        font-size: 16px;
    }

    /* Card Styles */
    .card {
        background: #fff;
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
        border-bottom: 1px solid #e0e0e0;
        text-align: center;
    }

    .card-content {
        padding: 20px;
    }

    .btn {
        background: #1e88e5;
        padding: 10px 20px;
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

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="activitiesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Quản lý Hoạt động
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="activitiesDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.activities.index') }}">Danh sách Hoạt động</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.activities.create') }}">Thêm Hoạt động</a></li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.managers.index') }}">Quản lý Thành viên</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('registrations.index') }}">Báo cáo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.issues.send') }}">Gửi Thông báo</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Sự kiện cho nút "Chọn Tất Cả"
            $('#selectAllButton').click(function() {
                $('.student-checkbox').prop('checked', function(i, value) {
                    return !value; // Đảo trạng thái checkbox
                });
            });

            // Tìm kiếm sinh viên
            $('#studentSearch').on('input', function() {
                const searchValue = $(this).val().toLowerCase();
                const rows = $('#studentTable tbody tr');

                let hasResults = false;

                rows.each(function() {
                    const studentId = $(this).find('td:nth-child(2)').text().toLowerCase();
                    const studentName = $(this).find('td:nth-child(3)').text().toLowerCase();

                    // Nếu từ khóa tìm kiếm khớp với mã sinh viên hoặc tên sinh viên, hiện hàng
                    if (studentId.includes(searchValue) || studentName.includes(searchValue)) {
                        $(this).show(); // Hiện hàng
                        hasResults = true;
                    } else {
                        $(this).hide(); // Ẩn hàng
                    }
                });

                // Hiển thị bảng bất kể có kết quả hay không
                $('#studentTable').show(); // Luôn hiển thị bảng
            });
        });
    </script>
    <script>
        function confirmDelete() {
            if (confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')) {
                // Nếu người dùng xác nhận, tìm và nhấn nút xóa
                event.target.closest('form').submit();
            }
        }
    </script>
    <!-- Bootstrap JS  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>