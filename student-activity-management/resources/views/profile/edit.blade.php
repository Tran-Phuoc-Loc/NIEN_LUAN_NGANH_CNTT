@extends('layouts.student')

@section('content')
<div class="container">
    <h2>Cập nhật thông tin cá nhân</h2>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Họ tên -->
        <div class="mb-3">
            <label for="name" class="form-label">Họ tên</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $student->name) }}" required>
        </div>

        <!-- Số điện thoại -->
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $student->phone) }}">
        </div>

        <!-- Ngày tham gia -->
        <div class="mb-3">
            <label for="joining_date" class="form-label">Ngày vào Đoàn</label>
            <input type="date" class="form-control" id="joining_date" name="joining_date" value="{{ old('joining_date', $student->joining_date) }}">
        </div>

        <!-- Nơi cấp thẻ -->
        <div class="mb-3">
            <label for="card_issuing_place" class="form-label">Nơi cấp thẻ</label>
            <input type="text" class="form-control" id="card_issuing_place" name="card_issuing_place" value="{{ old('card_issuing_place', $student->card_issuing_place) }}">
        </div>

        <!-- Avatar -->
        <div class="mb-3">
            <label for="avatar" class="form-label">Ảnh đại diện</label>
            <input type="file" class="form-control" id="avatar" name="avatar">
            @if($student->avatar)
            <img src="{{ asset('storage/' . $student->avatar) }}" alt="Avatar" class="mt-2" width="100">
            @endif
        </div>

        <!-- Nút lưu -->
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
