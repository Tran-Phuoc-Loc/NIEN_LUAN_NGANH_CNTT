@extends('layouts.student')

@section('content')
<div class="container">
    <h1>{{ $news->title }}</h1>
    <p class="text-muted">Đăng lúc: {{ $news->created_at->isoFormat('DD/MM/YYYY HH:mm') }}</p>

    <div class="news-detail">
        <!-- Hiển thị nội dung tin tức -->
        <div class="row">
            <div class="col-md-12">
                <!-- Ảnh minh họa chính -->
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="img-fluid mx-auto d-block" style="max-width: 60%; height: auto;">
                </div>

                <!-- Chia nội dung theo 'break' và đan xen ảnh bổ sung -->
                @php
                    // Tách nội dung ra thành các đoạn dựa trên <!--break-->
                    $content_parts = explode('<!--break-->', $news->content);
                    $image_index = 0; // Chỉ mục cho hình ảnh bổ sung
                @endphp

                @foreach($content_parts as $part)
                    <p>{!! nl2br(e($part)) !!}</p>

                    <!-- Hiển thị ảnh đan xen nếu có -->
                    @if($image_index < $news->images->count())
                        <div class="text-center my-4">
                            <img src="{{ asset('storage/' . $news->images[$image_index]->image_path) }}" alt="{{ $news->title }}" class="img-fluid mx-auto d-block" style="max-width: 35%; height: auto;">
                        </div>
                        @php $image_index++; @endphp
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
