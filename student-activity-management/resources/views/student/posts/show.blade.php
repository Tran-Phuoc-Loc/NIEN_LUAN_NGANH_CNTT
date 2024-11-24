@extends('layouts.student')

@section('content')
<div class="container py-4">
    <!-- Thẻ bài viết -->
    <div class="card shadow-lg border-0 mb-4 hover-border" style="transition: box-shadow 0.3s;">
        <!-- Hình ảnh bài viết -->
        <div class="image-container" style="height: 400px; overflow: hidden; border-radius: 15px;">
            @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}"
                class="card-img-top rounded-3 shadow-sm"
                alt="Image"
                style="object-fit: cover; width: 100%; height: 100%;">
            @else
            <img src="{{ asset('storage/default-image.jpg') }}"
                class="card-img-top rounded-3 shadow-sm"
                alt="Default Image"
                style="object-fit: cover; width: 100%; height: 100%;">
            @endif
        </div>

        <!-- Nội dung bài viết -->
        <div class="card-body">
            <h1 class="fw-bold text-primary mb-3">{{ $post->title }}</h1>
            <p class="text-muted mb-4 d-flex align-items-center">
                <i class="bi bi-person-circle me-2 text-primary"></i><strong>{{ $post->user->name }}</strong>
                &bull;
                <i class="bi bi-calendar-event me-2 ms-2 text-success"></i>{{ $post->created_at->format('d/m/Y H:i') }}
            </p>
            <div class="content fs-5" style="line-height: 1.8;">
                {{ $post->content }}
            </div>
        </div>

        <!-- Nút hành động -->
        <div class="card-footer bg-light d-flex justify-content-between align-items-center">
            <small class="text-muted">
                <i class="bi bi-person-circle"></i> <strong>{{ $post->user->name }}</strong> • {{ $post->created_at->format('d/m/Y H:i') }}
            </small>

            @if(auth()->check() && auth()->id() === $post->user_id)
            <div>
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm me-2" style="transition: all 0.3s;">
                    <i class="bi bi-pencil-square"></i> Chỉnh sửa
                </a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" style="transition: all 0.3s;" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?')">
                        <i class="bi bi-trash-fill"></i> Xóa
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Bình luận -->
    <div class="comments-section mt-5">
        <h3 class="fw-bold mb-4 text-secondary">Bình luận</h3>

        <!-- Hiển thị thông báo nếu có -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Form gửi bình luận -->
        <form method="POST" action="{{ route('comments.store', $post->id) }}" class="mb-4">
            @csrf
            <div class="input-group">
                <textarea name="comment" class="form-control" rows="3" placeholder="Viết bình luận..." required></textarea>
                <button type="submit" class="btn btn-primary btn-lg px-4">Gửi</button>
            </div>
        </form>

        <!-- Hiển thị danh sách bình luận -->
        @if($post->comments->isEmpty())
        <p class="text-muted">Chưa có bình luận nào. Hãy là người đầu tiên!</p>
        @else
        <div class="comments-list">
            @foreach($post->comments as $comment)
            <div class="comment-item mb-3 p-3 border rounded-3 shadow-sm bg-light position-relative">
                <div class="d-flex align-items-center mb-2">
                    @if($comment->user->student && $comment->user->student->avatar)
                    <img src="{{ asset('storage/' . $comment->user->student->avatar) }}" alt="User Avatar"
                        class="rounded-circle me-3"
                        style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #6c757d;">
                    @else
                    <img src="{{ asset('storage/students/avatars/default_avatar.png') }}" alt="Default Avatar"
                        class="rounded-circle me-3"
                        style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #6c757d;">
                    @endif


                    <div>
                        <strong class="d-block text-dark">{{ $comment->user->name }}</strong>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <p class="comment-content bg-white px-3 py-2 rounded">{{ $comment->comment }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection