@extends('layouts.student')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">Danh sách bài viết</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-success btn-lg shadow">+ Tạo bài viết mới</a>
    </div>

    <!-- Form tìm kiếm và lọc -->
    <form method="GET" action="{{ route('posts.index') }}" class="mb-4">
        <div class="row g-3 align-items-center">
            <!-- Tìm kiếm -->
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" 
                       placeholder="Tìm kiếm bài viết..." value="{{ request('search') }}">
            </div>

            <!-- Lọc theo người đăng -->
            <div class="col-md-3">
                <select name="author" class="form-select">
                    <option value="">-- Lọc theo người đăng --</option>
                    @foreach($authors as $author)
                    <option value="{{ $author->id }}" 
                            {{ request('author') == $author->id ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Nút lọc -->
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <!-- Lưới bài viết -->
    <div class="row g-4">
        @forelse($posts as $post)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <!-- Hình ảnh -->
                @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Image">
                @else
                <img src="{{ asset('storage/students/avatars/default_avatar.png') }}" class="card-img-top" alt="Default Image">
                @endif

                <!-- Nội dung bài viết -->
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none text-dark">{{ $post->title }}</a>
                    </h5>
                    <p class="card-text text-truncate" style="max-height: 3rem;">{{ $post->content }}</p>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <strong>{{ $post->user->name }}</strong> • {{ $post->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center">Không tìm thấy bài viết nào.</p>
        @endforelse
    </div>
</div>

@endsection
