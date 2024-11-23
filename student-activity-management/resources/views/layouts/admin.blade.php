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

    .admin-wrapper {
        display: flex;
        /* Sử dụng flexbox để tạo layout */
        height: 100vh;
        /* Đặt chiều cao bằng toàn bộ màn hình */
    }

    .sidebar {
        width: 250px;
        /* Chiều rộng của thanh điều hướng */
        background-color: #343a40;
        /* Màu nền của thanh điều hướng */
        color: white;
        /* Màu chữ */
        position: fixed;
        /* Cố định bên trái */
        height: 100%;
        /* Chiều cao bằng toàn bộ màn hình */
        overflow-y: auto;
        /* Thêm thanh cuộn nếu cần */
    }

    .content {
        margin-left: 250px;
        /* Khoảng cách từ nội dung chính sang thanh điều hướng */
        padding: 20px;
        /* Thêm khoảng cách trong nội dung chính */
        width: calc(100% - 250px);
        /* Chiếm phần còn lại */
    }

    /* Điều chỉnh khoảng cách và vị trí của dropdown trong sidebar */
    .sidebar .dropdown-menu {
        position: static;
        /* Không chồng lên các phần tử khác */
        margin-top: 0;
        /* Giảm khoảng cách với dropdown-toggle */
    }

    .sidebar .nav-item.dropdown:hover .dropdown-menu {
        display: block;
        margin-bottom: 10px;
        /* Đẩy phần tử dưới xuống */
    }
</style>

<body>
    <div class="admin-wrapper d-flex">
        <div class="sidebar">
            <header class="bg-dark text-white p-3">
                <div class="container">
                    <nav class="navbar navbar-expand-lg navbar-dark">
                        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                    </nav>
                </div>
            </header>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.students') }}">Danh sách sinh viên</a>
                </li>
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="issuesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Quản lý Thông báo
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="issuesDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.issues.index') }}">Danh sách Thông báo</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.issues.send') }}">Tạo Thông báo</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.news.index') }}">Tin tức</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>
        </div>

        <div class="content">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        function showStudentList(notificationId) {
            const studentList = @json($studentListData ?? []);

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
            console.log(studentList); // Kiểm tra dữ liệu danh sách sinh viên

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