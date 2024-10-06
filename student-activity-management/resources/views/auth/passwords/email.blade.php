@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Đặt Lại Mật Khẩu</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <button type="submit" class="btn btn-primary">Gửi Mã Đặt Lại Mật Khẩu</button>
    </form>
</div>
@endsection