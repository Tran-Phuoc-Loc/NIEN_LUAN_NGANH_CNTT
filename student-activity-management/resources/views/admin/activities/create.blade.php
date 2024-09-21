@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Thêm Hoạt Động Mới</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.activities.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên Hoạt Động</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="date">Ngày Diễn Ra</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
        </div>

        <div class="form-group">
            <label for="location">Địa Điểm</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="benefits">Quyền Lợi</label>
            <textarea class="form-control" id="benefits" name="benefits" rows="5">{{ old('benefits') }}</textarea>
        </div>

        <div class="form-group">
            <label for="registration_start">Ngày Bắt Đầu Đăng Ký</label>
            <input type="date" class="form-control" id="registration_start" name="registration_start" value="{{ old('registration_start') }}" required>
        </div>

        <div class="form-group">
            <label for="registration_end">Ngày Kết Thúc Đăng Ký</label>
            <input type="date" class="form-control" id="registration_end" name="registration_end" value="{{ old('registration_end') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Hoạt Động</button>
    </form>
</div>
@endsection

