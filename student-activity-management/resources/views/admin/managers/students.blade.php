@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Danh sách sinh viên</h1>

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Thanh tìm kiếm -->
    <form action="{{ route('admin.students') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, email, hoặc mã sinh viên" value="{{ request('search') }}">
            <select name="sort" class="form-control">
                <option value="">Thứ tự mặc định</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                <option value="email_asc" {{ request('sort') == 'email_asc' ? 'selected' : '' }}>Email A-Z</option>
                <option value="email_desc" {{ request('sort') == 'email_desc' ? 'selected' : '' }}>Email Z-A</option>
            </select>
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Mã đoàn viên</th>
                <th>Phone</th>
                <th>Ngày vào đoàn</th>
                <th>Nơi cấp thẻ đoàn</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{!! highlight($student->name, request('search')) !!}</td>
                <td>{!! highlight($student->email, request('search')) !!}</td>
                <td>{{ $student->student_id }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ $student->joining_date }}</td>
                <td>{{ $student->card_issuing_place }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection