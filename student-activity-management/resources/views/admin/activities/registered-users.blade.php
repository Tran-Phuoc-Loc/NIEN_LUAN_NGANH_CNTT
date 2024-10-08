@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh Sách Người Đăng Ký cho Hoạt Động: {{ $activity->name }}</h2>
    <form action="{{ route('admin.registrations.importAttendance', ['id' => $activity->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="attendance_file">Tải lên file điểm danh:</label>
            <input type="file" name="attendance_file" id="attendance_file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Nhập Điểm Danh</button>
    </form>
    <td>
        <a href="{{ route('admin.activities.unregistered-attendances', $activity->id) }}" class="btn btn-warning">Xem DS Điểm Danh Không Đăng Ký</a>
    </td>


    @if($registrations->isEmpty())
    <p class="text-center text-muted">Hiện tại chưa có ai đăng ký tham gia hoạt động này.</p>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Sinh Viên</th>
                <th>Tên Sinh Viên</th>
                <th>Email</th>
                <th>Điện Thoại</th>
                <th>Ngày Đăng Ký</th>
                <th>Check</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $registration)
            <tr>
                <td>{{ $registration->student_id}}</td>
                <td>{{ $registration->full_name }}</td>
                <td>{{ $registration->email }}</td>
                <td>{{ $registration->phone }}</td>
                <td>{{ $registration->created_at->format('d/m/Y H:i:s') }}</td>
                <td>
                    @if($registration->check)
                    <span class="badge bg-success">Đã điểm danh</span>
                    @else
                    <span class="badge bg-danger">Chưa điểm danh</span>
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection