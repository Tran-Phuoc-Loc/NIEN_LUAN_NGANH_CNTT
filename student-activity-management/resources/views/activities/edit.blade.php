@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Hoạt động</h1>
    <a href="{{ route('activities.create') }}" class="btn btn-primary">Thêm Hoạt động</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Tên hoạt động</th>
                <th>Mô tả hoạt động</th>
                <th>Ngày</th>
                <th>Địa điểm</th>
                <th>Ngày bắt đầu đăng ký</th>
                <th>Ngày kết thúc đăng ký</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $activity)
            <tr>
                <td>{{ $activity->name }}</td> // Tên hoạt động
                <td>{{ $activity->description }}</td> // Mô tả hoạt động
                <td>{{ $activity->date }}</td> // Ngày tổ chức
                <td>{{ $activity->location }}</td> // Địa điểm tổ chức
                <td>{{ $activity->registration_start }}</td> // Ngày bắt đầu đăng ký
                <td>{{ $activity->registration_end }}</td> // Ngày kết thúc đăng ký
                <td>
                    <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-warning">Sửa</a>
                    <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
