@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh sửa hoạt động</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên Hoạt Động</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $activity->name }}" required>
        </div>

        <div class="form-group">
            <label for="date">Ngày Diễn Ra</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ $activity->date->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label for="location">Địa Điểm</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $activity->location }}" required>
        </div>

        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea class="form-control" id="description" name="description" rows="5" required>{{ $activity->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="benefits">Quyền Lợi</label>
            <textarea class="form-control" id="benefits" name="benefits" rows="5">{{ old('benefits', $activity->benefits) }}</textarea>
        </div>

        <div class="form-group">
            <label for="registration_start">Ngày Bắt Đầu Đăng Ký</label>
            <input type="date" class="form-control" id="registration_start" name="registration_start" value="{{ $activity->registration_start->format('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label for="registration_end">Ngày Kết Thúc Đăng Ký</label>
            <input type="date" class="form-control" id="registration_end" name="registration_end" value="{{ $activity->registration_end->format('Y-m-d') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection