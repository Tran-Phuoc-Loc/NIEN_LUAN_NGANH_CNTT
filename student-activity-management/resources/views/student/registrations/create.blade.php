@extends('layouts.student')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-lg-6 col-sm-12">
        <h2 class="text-center mb-4">Đăng ký tham gia hoạt động: <strong>{{ $activity->name }}</strong></h2>

        <!-- Hiển thị thông báo thành công -->
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Hiển thị thông báo lỗi -->
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('registrations.store', $activity->id) }}" method="POST" class="bg-white p-5 rounded shadow-lg">
            @csrf
            <div class="mb-4">
                <label for="full_name" class="form-label fw-bold">Họ và tên</label>
                <input type="text" name="full_name" id="full_name" class="form-control form-control-lg rounded-pill" value="{{ old('full_name') }}" required placeholder="Nhập họ và tên của bạn">
                @error('full_name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="student_id" class="form-label fw-bold">Mã số sinh viên</label>
                <input type="text" name="student_id" id="student_id" class="form-control form-control-lg rounded-pill bg-light" value="{{ $user->student_id }}" readonly>
            </div>

            <div class="mb-4">
                <label for="batch" class="form-label fw-bold">Khóa</label>
                <select name="batch" id="batch" class="form-select form-select-lg rounded-pill" required>
                    <option value="">Chọn khóa</option>
                    <option value="K44">K44</option>
                    <option value="K45">K45</option>
                    <option value="K46">K46</option>
                    <option value="K47">K47</option>
                    <option value="K48">K48</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="department" class="form-label fw-bold">Khoa</label>
                <input type="text" name="department" id="department" class="form-control form-control-lg rounded-pill" value="{{ old('department', $user->department) }}" required placeholder="Nhập khoa của bạn">
                @error('department')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="batch" class="form-label fw-bold">Khóa</label>
                <select name="batch" id="batch" class="form-select form-select-lg rounded-pill" required>
                    <option value="">Chọn khóa</option>
                    <option value="2023-1">2023 - Kỳ 1</option>
                    <option value="2023-2">2023 - Kỳ 2</option>
                    <option value="2024-1">2024 - Kỳ 1</option>
                    <option value="2024-2">2024 - Kỳ 2</option>
                    <option value="2024-3">2025 - Kỳ 1  </option>
                </select>
            </div>

            <div class="mb-4">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" name="email" id="email" class="form-control form-control-lg rounded-pill bg-light" value="{{ $user->email }}" readonly>
            </div>

            <div class="mb-4">
                <label for="phone" class="form-label fw-bold">Số điện thoại (không bắt buộc)</label>
                <input type="text" name="phone" id="phone" class="form-control form-control-lg rounded-pill" value="{{ old('phone') }}" placeholder="Nhập số điện thoại">
                @error('phone')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill">Đăng ký</button>
        </form>
    </div>
</div>

<style>
    h2 {
        color: #34495e;
        font-family: 'Arial', sans-serif;
    }

    .form-label {
        color: #2c3e50;
    }

    .form-control,
    .form-select {
        border: 1px solid #ced4da;
        padding: 10px 20px;
        font-size: 1.1rem;
        margin-bottom: 15px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
    }

    .btn-primary {
        background-color: #3498db;
        border: none;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .shadow-lg {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .rounded-pill {
        border-radius: 50px;
    }

    .form-control-lg {
        padding: 10px 20px;
    }
</style>
@endsection