@extends('layouts.student')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Tin tức hoạt động đoàn/hội</h1>

    <!-- Form tìm kiếm và lọc -->
    <form action="{{ route('student.news.index') }}" method="GET" class="mb-4">
        <div class="d-flex mb-3">
            <!-- Tìm kiếm -->
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm tin tức..." value="{{ request('search') }}">
            
            <!-- Nút Tìm kiếm -->
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>

        <!-- Lọc theo ngày đăng -->
        <div class="d-flex mb-3">
            <input type="date" name="from_date" class="form-control me-2" value="{{ request('from_date') }}">
            <span class="mx-2 align-self-center">Đến</span>
            <input type="date" name="to_date" class="form-control me-2" value="{{ request('to_date') }}">
            <button class="btn btn-outline-primary" type="submit">Lọc</button>
        </div>

        <!-- Dropdown sắp xếp -->
        <div class="mb-3">
            <label for="sort_by" class="form-label">Sắp xếp theo:</label>
            <select name="sort_by" class="form-select" onchange="this.form.submit()">
                <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                <option value="alphabetical" {{ request('sort_by') == 'alphabetical' ? 'selected' : '' }}>Theo chữ cái</option>
            </select>
        </div>
    </form>

    <div class="row">
        @foreach ($news as $item)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $item->title }}</h5>
                    <p class="card-text">{!! Str::limit(str_replace('<!--break-->', '<br />', $item->content), 80) !!}</p>
                    <a href="{{ route('student.news.show', $item->id) }}" class="btn btn-primary mt-auto align-self-start">Đọc thêm</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Hiển thị phân trang -->
    <div class="d-flex justify-content-center mt-4">
        {{ $news->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .card:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease-in-out;
    }

    .card img {
        transition: transform 0.3s ease-in-out;
    }

    .card img:hover {
        transform: scale(1.1);
    }

    .btn-primary:hover {
        background-color: #0066cc;
    }

    .form-select, .form-control {
        border-radius: 8px;
    }
</style>
@endsection