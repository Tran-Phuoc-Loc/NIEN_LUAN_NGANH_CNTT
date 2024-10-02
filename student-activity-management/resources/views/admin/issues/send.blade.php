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

    <form action="{{ route('admin.issues.storeSend') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Chọn Sinh Viên</label>

            <!-- Bảng sinh viên chỉ hiển thị khi có từ khóa tìm kiếm -->
            @if (request()->has('search') && count($students) > 0)
            <div id="studentTable" class="table-responsive">
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
                                <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="student_{{ $student->id }}">
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
            @else
            <p class="text-center">Không tìm thấy sinh viên nào.</p>
            @endif
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Nội dung thông báo</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-warning w-100">Gửi Thông Báo</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$('#studentSearch').on('input', function() {
    const searchValue = $(this).val().toLowerCase();
    const studentTable = $('#studentTable');
    const rows = studentTable.find('tbody tr');

    let hasResults = false;

    rows.each(function() {
        const studentId = $(this).find('td:nth-child(2)').text().toLowerCase();
        const studentName = $(this).find('td:nth-child(3)').text().toLowerCase();

        // Nếu từ khóa tìm kiếm khớp với mã sinh viên hoặc tên sinh viên, hiện hàng
        if (studentId.includes(searchValue) || studentName.includes(searchValue)) {
            $(this).show();  // Hiện hàng
            hasResults = true;
        } else {
            $(this).hide();  // Ẩn hàng
        }
    });

    // Hiển thị bảng nếu có kết quả khớp, ngược lại thì ẩn bảng
    if (searchValue === '') {
        studentTable.hide(); // Ẩn bảng nếu không nhập từ khóa tìm kiếm
    } else {
        studentTable.toggle(hasResults); // Chỉ hiện bảng khi có kết quả
    }
});

// Kiểm tra hiển thị bảng sau khi tải trang
$(document).ready(function() {
    const rows = $('#studentTable tbody tr');
    const hasInitialResults = rows.filter(function() {
        return $(this).css('display') !== 'none';
    }).length > 0;

    $('#studentTable').toggle(hasInitialResults);
});

</script>
@endsection
