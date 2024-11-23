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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

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

    .admin-wrapper {
        display: flex;
        /* Sử dụng flexbox để tạo layout */
        height: 100vh;
        /* Đặt chiều cao bằng toàn bộ màn hình */
    }

    .sidebar {
        width: 250px;
        /* Chiều rộng của thanh điều hướng */
        color: white;
        /* Màu chữ */
    }

    .content {
        margin-left: 250px;
        /* Khoảng cách từ nội dung chính sang thanh điều hướng */
        padding: 20px;
        /* Thêm khoảng cách trong nội dung chính */
        width: calc(100% - 250px);
        /* Chiếm phần còn lại */
    }

    .group-header {
        background-color: #495057;
        /* Màu nền khác biệt */
        color: #8c78f3 !important;
        /* Màu chữ sáng */
        padding: 8px 15px;
        /* Khoảng cách bên trong */
        margin-top: 10px;
        /* Khoảng cách phía trên */
        border-radius: 5px;
        /* Góc bo tròn */
        font-weight: bold;
        /* Chữ đậm */
        text-transform: uppercase;
        /* Chữ in hoa */
    }
</style>

<body style="background-color:#f1f5f9;">
    <div class="container-fluid">
        <div class="row">
            <!-- Nút mở/đóng Sidebar (chỉ hiển thị trên màn hình nhỏ) -->
            <button id="toggleSidebar" class="btn btn-dark d-md-none" style="position: fixed; top: 10px; left: 10px; z-index: 1100;">
                ☰
            </button>

            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar bg-dark" style="position: fixed; height: 100%; overflow-y: auto;">
                <div class="position-sticky">
                    <!-- Header -->
                    <h4 class="text-center text-light py-3">Admin Dashboard</h4>

                    <!-- Thông tin người dùng -->
                    <div class="user-info text-center mb-4">
                        @if(auth()->check())
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('storage/students/avatars/admin_icon.png') }}"
                            alt="Profile of {{ auth()->user()->username }}"
                            class="rounded-circle" style="width: 70px; height: 75px;">
                        <h5 class="mt-2 text-white">{{ auth()->user()->username }}</h5>
                        <h6 class="text-secondary">{{ auth()->user()->email }}</h6>
                        <hr class="bg-secondary" style="margin: 10px 0;">
                        @endif
                    </div>

                    <!-- Nhóm: Tổng quan -->
                    <h6 class="text-light px-3 group-header">Tổng Quan</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-door me-2"></i> Home
                            </a>
                        </li>
                    </ul>
                    <hr class="bg-secondary">

                    <!-- Nhóm: Quản lý người dùng -->
                    <h6 class="text-light px-3 group-header">Quản Lý Người Dùng</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="{{ route('admin.students') }}">
                                <i class="bi bi-people me-2"></i> Danh sách sinh viên
                            </a>
                        </li>
                    </ul>
                    <hr class="bg-secondary">

                    <!-- Nhóm: Quản lý hoạt động -->
                    <h6 class="text-light px-3 group-header">Quản Lý Hoạt Động</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="{{ route('admin.activities.index') }}">
                                <i class="bi bi-calendar-event me-2"></i> Danh sách Hoạt động
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="{{ route('admin.activities.create') }}">
                                <i class="bi bi-plus-circle me-2"></i> Thêm Hoạt động
                            </a>
                        </li>
                    </ul>
                    <hr class="bg-secondary">

                    <!-- Nhóm: Quản lý thành viên -->
                    <h6 class="text-light px-3 group-header">Quản Lý Thành Viên</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="{{ route('admin.managers.index') }}">
                                <i class="bi bi-person-badge me-2"></i> Thành viên
                            </a>
                        </li>
                    </ul>
                    <hr class="bg-secondary">

                    <!-- Nhóm: Quản lý thông báo -->
                    <h6 class="text-light px-3 group-header">Quản Lý Thông Báo</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="{{ route('admin.issues.index') }}">
                                <i class="bi bi-bell me-2"></i> Danh sách Thông báo
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="{{ route('admin.issues.send') }}">
                                <i class="bi bi-pencil-square me-2"></i> Tạo Thông báo
                            </a>
                        </li>
                    </ul>
                    <hr class="bg-secondary">

                    <!-- Nhóm: Tin tức -->
                    <h6 class="text-light px-3 group-header">Tin Tức</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="{{ route('admin.news.index') }}">
                                <i class="bi bi-newspaper me-2"></i> Tin tức
                            </a>
                        </li>
                    </ul>
                    <hr class="bg-secondary">

                    <!-- Nhóm: Cài đặt & Đăng xuất -->
                    <h6 class="text-light px-3 group-header">Cài Đặt & Hệ Thống</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="#">
                                <i class="bi bi-gear me-2"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </li>
                    </ul>

                    <!-- Footer -->
                    <div class="text-center mt-4 mb-3 text-secondary">
                        <img src="{{ asset('storage/images/bookicon.png') }}" alt="Footer icon" style="width: 50px;">
                    </div>

                    <!-- Form đăng xuất -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>

            <div class="content">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        @if(isset($studentListData))
        @php
        // Đảm bảo rằng biến PHP được mã hóa đúng định dạng JSON
        $studentListData = json_encode($studentListData);
        @endphp
        @else
        @php
        // Xử lý trường hợp không có dữ liệu (mảng rỗng)
        $studentListData = json_encode([]);
        @endphp
        @endif
        <script>
            function showStudentList(notificationId) {
                // An toàn khi phân tích cú pháp dữ liệu JSON vào biến JavaScript
                var studentList = JSON.parse('{!! $studentListData !!}');


                // Tìm phần tử modal dựa trên notificationId
                const modalElement = document.getElementById('studentListModal' + notificationId);
                if (!modalElement) {
                    console.error(`Không tìm thấy modal với ID: studentListModal${notificationId}`);
                    return;
                }
                console.log(`Đang cố gắng mở modal với ID: studentListModal${notificationId}`);

                const students = studentList[notificationId] ? studentList[notificationId].students : [];
                const studentListElement = modalElement.querySelector('.student-list'); // Sử dụng class để dễ nhận biết
                studentListElement.innerHTML = ''; // Xóa nội dung cũ

                students.forEach(student => {
                    const li = document.createElement('li');
                    li.textContent = `${student.name} (${student.email})`;
                    studentListElement.appendChild(li);
                });

                // Hiển thị modal
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log(studentList);
            }

            document.addEventListener('DOMContentLoaded', function() {
                const sendToAll = document.getElementById('sendToAll');
                const selectAllButton = document.getElementById('selectAllButton');

                function toggleStudentSelection() {
                    const studentSelection = document.getElementById('studentSelection');
                    if (studentSelection) {
                        studentSelection.style.display = sendToAll && sendToAll.checked ? 'none' : 'block';
                    }
                }

                toggleStudentSelection();

                if (sendToAll) {
                    sendToAll.addEventListener('change', toggleStudentSelection);
                }

                if (selectAllButton) {
                    selectAllButton.addEventListener('click', function() {
                        const checkboxes = document.querySelectorAll('.student-checkbox');
                        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = !allChecked;
                        });
                    });
                }

                $('input[name="send_option"]').on('change', function() {
                    const selectedOption = $(this).val();

                    if (selectedOption === 'selected') {
                        $('#studentSelection').show(); // Hiện bảng chọn sinh viên
                    } else {
                        $('#studentSelection').hide(); // Ẩn bảng chọn sinh viên
                    }
                });

                // Gán sự kiện cho các nút "Xem danh sách"
                document.querySelectorAll('[data-notification-id]').forEach(button => {
                    button.addEventListener('click', function() {
                        const notificationId = this.getAttribute('data-notification-id');
                        showStudentList(notificationId);
                    });
                });

            });
            document.addEventListener('DOMContentLoaded', function() {
                // Lắng nghe sự kiện thay đổi trên radio button
                const sendToAll = document.getElementById('sendToAll');
                const sendToGroup = document.getElementById('sendToGroup');
                const sendToSelected = document.getElementById('sendToSelected');
                const studentSelection = document.getElementById('studentSelection');

                // Hàm kiểm tra và ẩn/hiện danh sách sinh viên
                function toggleStudentSelection() {
                    if (sendToAll.checked) {
                        studentSelection.style.display = 'none'; // Ẩn danh sách sinh viên khi chọn "Gửi Tất Cả"
                    } else {
                        studentSelection.style.display = 'block'; // Hiển thị danh sách sinh viên khi chọn "group" hoặc "selected"
                    }
                }

                // Gọi hàm kiểm tra ngay khi tải trang
                toggleStudentSelection();

                // Lắng nghe sự thay đổi trên các radio button
                sendToAll.addEventListener('change', toggleStudentSelection);
                sendToGroup.addEventListener('change', toggleStudentSelection);
                sendToSelected.addEventListener('change', toggleStudentSelection);
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