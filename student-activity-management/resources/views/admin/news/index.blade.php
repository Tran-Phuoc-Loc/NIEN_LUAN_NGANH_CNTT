@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Quản lý Tin Tức</h1>

    <!-- Thông tin tổng quát -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="mb-0">Tổng số tin tức: <strong>{{ $newsList->total() }}</strong></p>
        <a href="{{ route('admin.news.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Tạo Tin tức
        </a>
    </div>

    <!-- Thanh tìm kiếm và bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.news.index') }}" method="GET" class="row g-3 align-items-end">
                <!-- Tìm kiếm -->
                <div class="col-md-4">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Tìm kiếm tin tức..." value="{{ request('search') }}">
                </div>
                <!-- Bộ lọc ngày -->
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Ngày Bắt Đầu</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Ngày Kết Thúc</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <!-- Nút lọc -->
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Thông báo -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Danh sách tin tức -->
    <div class="row g-4">
        @forelse($newsList as $news)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="row g-0 align-items-center">
                    <!-- Hình ảnh -->
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $news->image) }}" class="img-fluid rounded-start" alt="{{ $news->title }}" style="object-fit: cover; height: 100%;">
                    </div>
                    <!-- Nội dung -->
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $news->title }}</h5>
                            <p class="card-text text-truncate">{{ Str::limit(str_replace('<!--break-->', '', $news->content), 40) }}</p>
                            <p class="text-muted">Đăng lúc: {{ $news->created_at->isoFormat('DD/MM/YYYY HH:mm') }}</p>
                            <!-- Thao tác -->
                            <div class="d-flex">
                                <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-primary btn-sm me-2">
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
        @empty
        <p class="text-center text-muted">Không có tin tức nào phù hợp.</p>
        @endforelse
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center mt-4">
        {{ $newsList->onEachSide(1)->links() }}
    </div>
</div>

<style>
    .card-body h5 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #333;
    }

    .btn-sm {
        font-size: 0.9rem;
    }

    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card img {
        border-radius: 0.25rem;
    }

    .card {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>
@endsection