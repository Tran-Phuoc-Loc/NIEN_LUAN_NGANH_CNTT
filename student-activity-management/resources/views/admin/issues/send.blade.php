@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Gửi Thông Báo đến Sinh Viên</h2>

    <!-- Form tìm kiếm -->
    <form action="{{ route('admin.issues.send') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sinh viên" value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>
    </form>

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (isset($students) && count($students) > 0)
    <form action="{{ route('admin.issues.storeSend') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Chọn Sinh Viên</label>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Mã Sinh Viên</th>
                            <th>Tên</th>
                            <th>Lớp</th>
                            <th>Khoa</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                        <tr>
                            <td>
                                <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}">
                            </td>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->student ? $student->student->class : 'N/A' }}</td>
                            <td>{{ $student->student ? $student->student->department : 'N/A' }}</td>
                            <td>{{ $student->email }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Nội dung thông báo</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-warning w-100">Gửi Thông Báo</button>
    </form>
        <!-- Liên kết phân trang -->
        {{ $students->links() }}
    @else
    <p class="text-center">Không tìm thấy sinh viên nào.</p>
    @endif
</div>
@endsection