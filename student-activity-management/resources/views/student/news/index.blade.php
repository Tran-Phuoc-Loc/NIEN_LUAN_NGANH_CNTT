@extends('layouts.student')

@section('content')
<div class="container">
    <h1 class="my-4">Tin tức hoạt động đoàn/hội</h1>
    <div class="row">
        @foreach ($news as $item)
        <div class="col-md-4 d-flex align-items-stretch">
            <div class="card mb-4 shadow-sm">
                <!-- Giới hạn chiều cao hình ảnh và căn giữa -->
                @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $item->title }}</h5>
                    <p class="card-text">{{ Str::limit($item->content, 100) }}</p>
                    <!-- Nút Đọc thêm luôn nằm dưới cùng -->
                    <a href="{{ route('student.news.show', $item->id) }}" class="btn btn-primary mt-auto align-self-start">Đọc thêm</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<style>
    .card:hover {
        transform: scale(1.02);
        transition: transform 0.2s;
    }

    .btn:hover {
        background-color: #0056b3;
        /* Màu nền mới khi hover */
    }
</style>
@endsection