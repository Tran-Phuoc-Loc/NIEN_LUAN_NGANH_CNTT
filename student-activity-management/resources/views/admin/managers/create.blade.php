@extends('layouts.admin')

@section('content')
<div class="container">
    <!-- Thông báo thành công hoặc lỗi -->
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <h1>Thêm Sinh Viên</h1>
    <!-- Form import file CSV/Excel -->
    <h2>Thêm Sinh Viên Từ File Excel/CSV</h2>
    <form action="{{ route('admin.managers.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Chọn file Excel/CSV</label>
            <input type="file" id="file" name="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Import Sinh Viên</button>
    </form>
    <form id="studentForm" action="{{ route('admin.managers.store') }}" method="POST">
        @csrf
        <div id="studentFields">
            <div class="student-field">
                <div class="form-group">
                    <label for="student_id_1">Mã Sinh Viên</label>
                    <input type="text" id="student_id_1" name="student_id[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="name_1">Tên</label>
                    <input type="text" id="name_1" name="name[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email_1">Email</label>
                    <input type="email" id="email_1" name="email[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="phone_1">Số Điện Thoại</label>
                    <input type="text" id="phone_1" name="phone[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="joining_date_1">Ngày vào đoàn</label>
                    <input type="date" id="joining_date_1" name="joining_date[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="card_issuing_place_1">Nơi cấp thẻ đoàn</label>
                    <input type="text" id="card_issuing_place_1" name="card_issuing_place[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_1">Mật Khẩu</label>
                    <input type="password" id="password_1" name="password[]" class="form-control" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Thêm Sinh Viên</button>
    </form>
</div>

@endsection