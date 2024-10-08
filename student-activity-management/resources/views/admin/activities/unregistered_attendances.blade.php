@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh Sách Sinh Viên Điểm Danh Mà Không Đăng Ký</h2>

    @if($unregisteredAttendances->isEmpty())
    <div class="alert alert-info">Không có sinh viên nào điểm danh mà không đăng ký.</div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Sinh Viên</th>
                <th>Tên Sinh Viên</th>
                <th>Email</th>
                <th>Điện Thoại</th>
                <th>Khóa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unregisteredAttendances as $attendance)
            <tr>
                <td>{{ $attendance->student_id }}</td>
                <td>{{ $attendance->full_name }}</td>
                <td>{{ $attendance->email ?? 'Không có email' }}</td>
                <td>{{ $attendance->phone ?? 'Không có số điện thoại' }}</td>
                <td>{{ $attendance->batch }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection