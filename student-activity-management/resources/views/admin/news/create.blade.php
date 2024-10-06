@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Tạo Tin Tức Mới</h1>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
            <small class="form-text text-muted">Sử dụng <code>&lt;!--break--&gt;</code> để chia các đoạn văn trong nội dung.</small>
        </div>

        <div class="form-group">
            <label for="image">Ảnh minh họa</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="images">Ảnh bổ sung (chọn nhiều ảnh)</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Tạo mới</button>
    </form>
</div>
@endsection
