@extends('layouts.student')

@section('content')
<div class="container">
    <h2>Thông tin cá nhân</h2>
    
    <div class="card">
        <div class="card-header">
            Thông tin người dùng
        </div>
        <div class="card-body">
        <p><strong>Tên:</strong> {{ $user->name }}</p>
            <p><strong>MSSV:</strong> {{ $user->student_id }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            
            @if($student)
            <p><strong>Số điện thoại:</strong> {{ $student->phone }}</p>
            <p><strong>Ngày vào đoàn:</strong> {{ $student->joining_date }}</p>
            <p><strong>Nơi cấp thẻ đoàn:</strong> {{ $student->card_issuing_place }}</p>
            @else
            <p>Thông tin sinh viên không có.</p>
            @endif
            <p><strong>Các hoạt động đã tham gia</strong> </p>
        </div>
    </div>
</div>
@endsection            