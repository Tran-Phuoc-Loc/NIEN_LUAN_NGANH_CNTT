@extends('layouts.student')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">{{ $activity->name }}</h2>
        </div>
        <div class="card-body">
            <p><strong>Ngày:</strong> {{ $activity->date->format('d/m/Y') }}</p>
            <p><strong>Địa điểm:</strong> {{ $activity->location }}</p>

            @if($activity->description)
                <p><strong>Mô tả:</strong></p>
                <div class="border p-3 rounded" style="white-space: pre-wrap;">{{ $activity->description }}</div>
            @endif

            <p class="mt-4">🗓️ Hãy chuẩn bị sẵn sàng để tham gia sự kiện thú vị này! Chúng ta sẽ có những trải nghiệm tuyệt vời cùng nhau!</p>

            @if($activity->benefits)
                <h4 class="mt-4">📜 Quyền lợi khi tham gia:</h4>
                <div class="border p-3 rounded" style="white-space: pre-wrap;">{{ $activity->benefits }}</div>
            @endif

            <div class="mt-4">
                @php
                    $registeredCount = $activity->registrations()->count();
                @endphp

                @if($registeredCount < $activity->max_participants && now()->between($activity->registration_start, $activity->registration_end))
                    <!-- Nếu còn slot đăng ký và trong thời gian đăng ký -->
                    <a href="{{ route('registrations.create', ['id' => $activity->id]) }}" class="btn btn-success btn-lg">Đăng ký tham gia ngay!</a>
                    <p class="text-success mt-3">🎉 Đừng bỏ lỡ cơ hội gặp gỡ bạn bè và khám phá điều mới mẻ nhé!</p>
                @elseif($registeredCount >= $activity->max_participants)
                    <!-- Nếu đã đủ số người đăng ký -->
                    <p class="text-danger mt-3">🚫 Đã đủ số người đăng ký. Bạn không thể tham gia sự kiện này nữa.</p>
                @elseif(now()->lt($activity->registration_start))
                    <!-- Nếu chưa đến thời gian đăng ký -->
                    <p class="text-warning mt-3">🕒 Thời gian đăng ký chưa đến! Hãy chờ đợi và theo dõi thông báo để không bỏ lỡ cơ hội nhé!</p>
                @else
                    <!-- Nếu hết hạn đăng ký -->
                    <p class="text-danger mt-3">⏳ Thời gian đăng ký đã hết. Hãy theo dõi các sự kiện tiếp theo để không bỏ lỡ nhé!</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection