@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <!-- Thông báo -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Lỗi!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Tiêu đề -->
    <div class="mb-4">
        <h1 class="display-5 fw-bold text-primary">Thêm Sinh Viên</h1>
        <p class="text-muted">Quản lý sinh viên thêm trực tiếp hoặc nhập file Excel/CSV.</p>
    </div>

    <!-- Thêm từ file Excel/CSV -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <h2 class="card-title h4 mb-3">Thêm Sinh Viên Từ File Excel/CSV</h2>
            <form action="{{ route('admin.managers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Chọn file Excel/CSV</label>
                    <input type="file" id="file" name="file" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100">Import Sinh Viên</button>
            </form>
        </div>
    </div>

    <!-- Thêm sinh viên trực tiếp -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title h4 mb-3">Thêm Sinh Viên Thủ Công</h2>
            <form id="studentForm" action="{{ route('admin.managers.store') }}" method="POST">
                @csrf
                <div id="studentFields">
                    <div class="student-field border rounded p-3 mb-3">
                        <h5 class="mb-3">Sinh Viên #1</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="student_id_1" class="form-label">Mã Sinh Viên</label>
                                <input type="text" id="student_id_1" name="student_id[]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="name_1" class="form-label">Tên</label>
                                <input type="text" id="name_1" name="name[]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="email_1" class="form-label">Email</label>
                                <input type="email" id="email_1" name="email[]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="phone_1" class="form-label">Số Điện Thoại</label>
                                <input type="text" id="phone_1" name="phone[]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="joining_date_1" class="form-label">Ngày vào đoàn</label>
                                <input type="date" id="joining_date_1" name="joining_date[]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="card_issuing_place_1" class="form-label">Nơi cấp thẻ đoàn</label>
                                <input type="text" id="card_issuing_place_1" name="card_issuing_place[]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="password_1" class="form-label">Mật Khẩu</label>
                                <input type="password" id="password_1" name="password[]" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">Thêm Sinh Viên</button>
            </form>
        </div>
    </div>
</div>
@endsection