@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Quản lý Tin Tức</h1>

    <!-- Thông tin số lượng bài viết -->
    <div class="mb-3">
        <p>Tổng số tin tức: <strong>{{ $newsList->total() }}</strong></p>
    </div>

    <!-- Thanh tìm kiếm -->
    <form action="{{ route('admin.news.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tin tức..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-secondary" type="submit">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <!-- Bộ lọc theo ngày -->
    <form action="{{ route('admin.news.index') }}" method="GET" class="mb-4">
        <div class="form-row">
            <div class="col">
                <p>Ngày Bắt Đầu</p>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col">
                <p>Ngày Kết Thúc</p>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Lọc</button>
            </div>
        </div>
    </form>

    <!-- Nút tạo tin tức -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.news.create') }}" class="btn btn-success">Tạo Tin tức</a>
    </div>

    <!-- Hiển thị thông báo thành công -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        @foreach($newsList as $news)
        <div class="col-md-12 mb-4">
            <div class="card p-3">
                <div class="row no-gutters align-items-center">
                    <!-- Cột chứa ảnh, điều chỉnh kích thước ảnh -->
                    <div class="col-md-3">
                        <img src="{{ asset('storage/' . $news->image) }}" class="img-fluid" alt="{{ $news->title }}" style="max-height: 150px;">
                    </div>

                    <!-- Cột chứa tiêu đề, nội dung và thao tác -->
                    <div class="col-md-9">
                        <div class="card-body">
                            <h5 class="card-title">{{ $news->title }}</h5>
                            <p class="card-text">{{ Str::limit($news->content, 100) }}</p>
                            <p class="text-muted">Đăng lúc: {{ $news->created_at->isoFormat('DD/MM/YYYY HH:mm') }}</p>

                            <!-- Các nút thao tác -->
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa tin tức này?')">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center">
        {{ $newsList->onEachSide(1)->links() }} <!-- Hiển thị phân trang với số trang gần bên -->
    </div>

</div>
<style>
    .btn-group .btn {
        margin-right: 5px;
        /* Thêm khoảng cách giữa các nút */
    }

    .btn:hover {
        opacity: 0.8;
        /* Thêm hiệu ứng hover cho nút */
    }
</style>
@endsection