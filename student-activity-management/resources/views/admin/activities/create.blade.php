@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Thêm Hoạt Động Mới</h2>
        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
    </div>

    <!-- Hiển thị thông báo lỗi -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <h5 class="fw-bold">Lỗi trong quá trình nhập liệu:</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.activities.store') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold">Tên Hoạt Động</label>
                        <input type="text" class="form-control " id="name" name="name" placeholder="Nhập tên hoạt động" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="date" class="form-label fw-bold">Ngày Diễn Ra</label>
                        <input type="date" class="form-control " id="date" name="date" value="{{ old('date') }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="location" class="form-label fw-bold">Địa Điểm</label>
                        <input type="text" class="form-control " id="location" name="location" placeholder="Nhập địa điểm" value="{{ old('location') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="max_participants" class="form-label fw-bold">Số Người Đăng Ký Tối Đa</label>
                        <input type="number" class="form-control" id="max_participants" name="max_participants" value="{{ old('max_participants') }}" required min="0" max="200" placeholder="Nhập số lượng tối đa">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Mô Tả</label>
                    <textarea class="form-control " id="description" name="description" rows="4" placeholder="Nhập mô tả hoạt động" required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="benefits" class="form-label fw-bold">Quyền Lợi</label>
                    <textarea class="form-control " id="benefits" name="benefits" rows="4" placeholder="Nhập quyền lợi (nếu có)">{{ old('benefits') }}</textarea>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="registration_start" class="form-label fw-bold">Ngày Bắt Đầu Đăng Ký</label>
                        <input type="date" class="form-control " id="registration_start" name="registration_start" value="{{ old('registration_start') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="registration_end" class="form-label fw-bold">Ngày Kết Thúc Đăng Ký</label>
                        <input type="date" class="form-control " id="registration_end" name="registration_end" value="{{ old('registration_end') }}" required>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-check-circle"></i> Thêm Hoạt Động
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection