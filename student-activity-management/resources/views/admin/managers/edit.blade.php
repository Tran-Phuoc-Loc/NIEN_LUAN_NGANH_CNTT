@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Chỉnh Sửa Thông Tin Sinh Viên</h2>

    <form action="{{ route('admin.managers.update', $student->id) }}" method="POST">
        @csrf
        @method('POST')

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
            <label for="class" class="form-label">Lớp</label>
            <input type="text" class="form-control" id="class" name="class" value="{{ $student->class }}">
        </div>

        <div class="mb-3">
            <label for="department" class="form-label">Khoa</label>
            <input type="text" class="form-control" id="department" name="department" value="{{ $student->department }}">
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