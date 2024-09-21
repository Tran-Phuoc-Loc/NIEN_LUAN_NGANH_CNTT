@extends('layouts.student')

@section('content')
<div class="container">
    <h2>{{ $activity->name }}</h2>
    <p><strong>Ngày:</strong> {{ $activity->date->format('d/m/Y') }}</p>
    <p><strong>Địa điểm:</strong> {{ $activity->location }}</p>
    <p><strong>Mô tả:</strong> {{ $activity->description }}</p>
    <p>🗓️ Hãy chuẩn bị sẵn sàng để tham gia sự kiện thú vị này! Chúng ta sẽ có những trải nghiệm tuyệt vời cùng nhau!</p>
    
    <h4>📜 Quyền lợi khi tham gia:</h4>
    <ul>
        <li>✅ Được cộng điểm rèn luyện và xét tiêu chí Hội nhập tốt của phong trào SV5T.</li>
        <li>🏆 Xét khen thưởng cho chi đoàn có số lượng đoàn viên tham gia trên 90% và cộng 50% số điểm khen thưởng cho tất cả đoàn viên.</li>
    </ul>

    @if(now()->between($activity->registration_start, $activity->registration_end))
        <a href="{{ route('registrations.create', ['id' => $activity->id]) }}" class="btn">Đăng ký tham gia ngay!</a>
        <p>🎉 Đừng bỏ lỡ cơ hội gặp gỡ bạn bè và khám phá điều mới mẻ nhé!</p>
    @elseif(now()->lt($activity->registration_start))
        <p>🕒 Thời gian đăng ký chưa đến! Hãy chờ đợi và theo dõi thông báo để không bỏ lỡ cơ hội nhé!</p>
    @else
        <p>⏳ Thời gian đăng ký đã hết. Hãy theo dõi các sự kiện tiếp theo để không bỏ lỡ nhé!</p>
    @endif
</div>
@endsection
