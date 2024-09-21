@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Quản Lý Hoạt Động</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Form tìm kiếm -->
    <form action="{{ route('admin.activities.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Nhập tên hoạt động..." value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Tìm Kiếm</button>
        </div>
    </form>

    <!-- Nút tạo hoạt động mới -->
    <a href="{{ route('admin.activities.create') }}" class="btn btn-success mb-3">Thêm Hoạt Động</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên Hoạt Động</th>
                <th>Ngày Diễn Ra</th>
                <th>Hạn Đăng Ký</th>
                <th>Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
            <tr>
                <td>{{ $activity->name }}</td>
                <td>{{ $activity->date->format('d/m/Y') }}</td>
                <td>{{ $activity->registration_end->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-primary">Chỉnh sửa</a>
                    <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa hoạt động này?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $activities->links() }} <!-- Phân trang -->
</div>
@endsection
