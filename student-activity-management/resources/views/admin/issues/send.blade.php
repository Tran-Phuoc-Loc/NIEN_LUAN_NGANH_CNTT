@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Gửi Thông Báo đến Sinh Viên</h2>

    <!-- Form tìm kiếm -->
    <form action="{{ route('admin.issues.send') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sinh viên" value="{{ request('search') }}" id="studentSearch">
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

    <form action="{{ route('admin.issues.storeSend') }}" method="POST" id="send-notification-form">
        @csrf

        <!-- Tùy chọn Gửi Thông Báo -->
        <div class="mb-3">
            <label class="form-label">Chọn Phương Thức Gửi Thông Báo</label>
            <div>
                <input type="radio" id="sendToAll" name="send_option" value="all" checked>
                <label for="sendToAll">Gửi Tất Cả Sinh Viên</label>
            </div>
            <div>
                <input type="radio" id="sendToGroup" name="send_option" value="group">
                <label for="sendToGroup">Gửi Một Nhóm Lớn (50 Sinh Viên Trở Lên)</label>
            </div>
            <div>
                <input type="radio" id="sendToSelected" name="send_option" value="selected">
                <label for="sendToSelected">Gửi Đến Sinh Viên Cụ Thể</label>
            </div>
        </div>

        <!-- Bảng chọn sinh viên, chỉ hiển thị nếu tùy chọn "group" hoặc "selected" được chọn -->
        <div id="studentSelection" class="mb-3" style="display:none;">
            <label class="form-label">Chọn Sinh Viên</label>
            <div class="table-responsive">
                <table class="table" id="studentTable">
                    <thead>
                        <tr>
                            <th><button type="button" class="btn btn-success" id="selectAllButton">Chọn Tất Cả</button></th>
                            <th>Mã Sinh Viên</th>
                            <th>Tên</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($users) && $users->count() > 0)
                        @foreach ($users as $student)
                        <tr>
                            <td><input class="form-check-input student-checkbox" type="checkbox" name="user_ids[]" value="{{ $student->id }}"></td>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                        </tr>
                        @endforeach
                        @else
                        @foreach ($allUsers as $student)
                        <tr>
                            <td><input class="form-check-input student-checkbox" type="checkbox" name="user_ids[]" value="{{ $student->id }}"></td>
                            <td>{{ $student->student_id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            {{ isset($users) ? $users->links() : $allUsers->links() }}
        </div>

        <!-- Trường nhập nội dung thông báo -->
        <div class="mb-3">
            <label for="message" class="form-label">Nội dung thông báo</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-warning w-100">Gửi Thông Báo</button>
    </form>
</div>
@endsection