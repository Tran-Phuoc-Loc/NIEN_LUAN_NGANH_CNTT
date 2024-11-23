@extends('layouts.admin')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="text-primary">Quản Lý Hoạt Động</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.activities.create') }}" class="btn btn-success btn-lg">
                <i class="bi bi-plus-circle"></i> Thêm Hoạt Động
            </a>
        </div>
    </div>

    <!-- Thông báo thành công -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Form tìm kiếm -->
    <form action="{{ route('admin.activities.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control form-control-lg" placeholder="Nhập tên hoạt động..." value="{{ request('search') }}">
            <button class="btn btn-primary btn-lg" type="submit">
                <i class="bi bi-search"></i> Tìm Kiếm
            </button>
        </div>
    </form>

    <!-- Bảng quản lý hoạt động -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Tên Hoạt Động</th>
                        <th>Ngày Diễn Ra</th>
                        <th>Hạn Đăng Ký</th>
                        <th>Số Người Đã Đăng Ký</th> <!-- Cột mới -->
                        <th>Trạng Thái</th>
                        <th>Thao Tác</th>
                        <th>Danh Sách</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                    <tr>
                        <td>{{ $activity->name }}</td>
                        <td>{{ $activity->date->format('d/m/Y') }}</td>
                        <td>{{ $activity->registration_end->format('d/m/Y') }}</td>
                        <td>
                            <!-- Hiển thị số người đã đăng ký -->
                            {{ $activity->registrations_count }} 
                        </td>
                        <td>
                            <span class="badge {{ $activity->is_hidden ? 'bg-danger' : 'bg-success' }}">
                                {{ $activity->is_hidden ? 'Ẩn' : 'Hiện' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i> Chỉnh sửa
                            </a>
                            <form action="{{ route('admin.activities.destroyOrHide', $activity->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <select name="action" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">Chọn hành động</option>
                                    <option value="show">Hiện</option>
                                    <option value="hide">Ẩn</option>
                                    <option value="delete">Xóa</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.activities.registered-users', $activity->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-list-ul"></i> Xem danh sách
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phân trang -->
    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</div>
@endsection