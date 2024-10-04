@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh Sách Người Đăng Ký cho Hoạt Động: {{ $activity->name }}</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên Sinh Viên</th>
                <th>Email</th>
                <th>Điện Thoại</th>
                <th>Khoa</th>
                <th>Khóa</th>
                <th>Ngày Đăng Ký</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $registration)
            <tr>
                <td>{{ $registration->full_name }}</td>
                <td>{{ $registration->email }}</td>
                <td>{{ $registration->phone }}</td>
                <td>{{ $registration->department }}</td>
                <td>{{ $registration->batch }}</td>
                <td>{{ $registration->created_at->format('d/m/Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection