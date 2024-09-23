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
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Mã sinh viên</th>
                <th>Phone</th>
                <th>Lớp</th>
                <th>Khoa</th>
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
                <td>{{ $student->class }}</td>
                <td>{{ $student->department }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection