@extends('layouts.student')

@section('content')
<div class="container py-4">
    <h1 class="fw-bold text-primary mb-4">Chỉnh sửa bài viết</h1>
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Tiêu đề -->
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>

        <!-- Nội dung -->
        <div class="mb-3">
            <label for="content" class="form-label">Nội dung</label>
            <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content', $post->content) }}</textarea>
        </div>

        <!-- Hình ảnh -->
        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh (tùy chọn)</label>
            <input type="file" name="image" id="image" class="form-control">
            @if($post->image)
            <p class="mt-2">Hình ảnh hiện tại: <img src="{{ asset('storage/' . $post->image) }}" alt="Image" style="max-width: 100px;"></p>
            @endif
        </div>

        <!-- Nút Cập nhật -->
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
