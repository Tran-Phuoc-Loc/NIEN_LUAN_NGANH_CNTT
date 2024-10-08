@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Chỉnh Sửa Thông Tin Sinh Viên</h2>

    <form action="{{ route('admin.managers.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $student->email }}" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Số Điện Thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $student->phone }}">
        </div>

        <div class="mb-3">
            <label for="joining_date" class="form-label">Ngày vào đoàn</label>
            <input type="date" class="form-control" id="joining_date" name="joining_date" value="{{ $student->joining_date }}">
        </div>

        <div class="mb-3">
            <label for="card_issuing_place" class="form-label">Nơi cấp thẻ đoàn</label>
            <input type="text" class="form-control" id="card_issuing_place" name="card_issuing_place" value="{{ $student->card_issuing_place }}">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Quyền</label>
            <select name="role" class="form-control" required>
                <option value="user" {{ $student->role == 'user' ? 'selected' : '' }}>Người dùng</option>
                <option value="admin" {{ $student->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật Thông Tin</button>
    </form>
</div>
@endsection