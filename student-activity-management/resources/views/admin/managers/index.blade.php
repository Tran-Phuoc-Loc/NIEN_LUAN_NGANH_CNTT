@extends('layouts.admin')

@section('content')
<div class="container my-5">
    <!-- Tiêu đề -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary fw-bold">Quản lý Thành Viên</h1>
        <a href="{{ route('admin.managers.create') }}" class="btn btn-success btn-lg">Thêm Sinh Viên</a>
    </div>

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

    <!-- Thanh tìm kiếm -->
    <form action="{{ route('admin.managers.index') }}" method="GET" class="input-group mb-4">
        <input type="text" name="search" class="form-control form-control-lg" placeholder="Tìm kiếm theo tên hoặc email" value="{{ request('search') }}">
        <button class="btn btn-primary btn-lg" type="submit">Tìm kiếm</button>
    </form>

    <!-- Bảng thành viên -->
    <div class="table-responsive shadow-sm">
        <table class="table table-hover table-bordered">
            <thead class="table-primary">
                <tr>
                    <th class="text-center">Tên</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Quyền</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="align-middle">{!! highlight($user->name, request('search')) !!}</td>
                    <td class="align-middle">{!! highlight($user->email, request('search')) !!}</td>
                    <td class="align-middle">
                        <form action="{{ route('admin.managers.updateRole', $user->id) }}" method="POST" class="d-flex align-items-center justify-content-center">
                            @csrf
                            <select name="role" class="form-select" onchange="this.form.submit()">
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Người dùng</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            </select>
                        </form>
                    </td>
                    <td class="align-middle text-center">
                        <a href="{{ route('admin.managers.edit', $user->id) }}" class="btn btn-warning btn-sm me-2">Chỉnh sửa</a>
                        <form action="{{ route('admin.managers.destroy', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(this)">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        <!-- Phân trang -->
        <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
</div>

<!-- Xác nhận xóa -->
<script>
    function confirmDelete(button) {
        if (confirm("Bạn có chắc chắn muốn xóa thành viên này không?")) {
            button.closest('form').submit();
        }
    }
</script>
@endsection
