@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Chỉnh sửa Tin Tức</h1>

    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ $news->title }}" required>
        </div>

        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" class="form-control" rows="5" required>{{ $news->content }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Ảnh hiện tại</label>
            @if($news->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" width="200">
                </div>
            @endif
            <label for="image">Thay đổi ảnh</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
