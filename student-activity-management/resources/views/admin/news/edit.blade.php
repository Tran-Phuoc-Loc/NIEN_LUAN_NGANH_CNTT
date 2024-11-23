@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Chỉnh sửa Tin Tức</h1>

    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}" required>
        </div>

        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" class="form-control" rows="5" required>{!! old('content', str_replace('<!--break-->', '<br />', $news->content)) !!}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Ảnh hiện tại</label>
            @if($news->image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" width="200">
            </div>
            @else
            <p>Không có ảnh hiện tại.</p>
            @endif
        </div>

        <div class="form-group">
            <label for="image">Thay đổi ảnh</label>
            <input type="file" name="image" class="form-control">
            <small class="form-text text-muted">Chọn ảnh mới nếu muốn thay đổi.</small>
        </div>

        <div class="form-group">
            <label for="images">Ảnh phụ</label>

            <!-- Lưới ảnh phụ -->
            <div class="row">
                @foreach($additionalImages as $image)
                <div class="col-md-2 mb-2">
                    <div class="card">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Ảnh phụ" class="card-img-top" width="100">
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Input file cho ảnh phụ -->
            <input type="file" name="images[]" class="form-control" multiple>
            <small class="form-text text-muted">Chọn ảnh bổ sung (nếu có).</small>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection