@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Danh sách sinh viên</h1>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Mã sinh viên</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->student_id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
