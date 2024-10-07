@extends('layouts.admin')

@section('content')

<div class="container">

    <h2 class="mb-4">Chào mừng, Admin!</h2>

    <div class="grid">
        <!-- Thống kê tổng quan -->
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                Thống kê tổng quan
            </div>
            <div class="card-content p-4">
                <div class="stat-grid d-flex justify-content-around">
                    <div class="stat-item text-center">
                        <div class="stat-number display-4 text-primary">{{ $totalMembers }}</div>
                        <div>Tổng số thành viên</div>
                    </div>
                    <div class="stat-item text-center">
                        <div class="stat-number display-4 text-primary">{{ $totalActivities }}</div>
                        <div>Tổng số hoạt động</div>
                    </div>
                    <div class="stat-item text-center">
                        <div class="stat-number display-4 text-primary">{{ $visibleActivitiesCount }}</div>
                        <div>Hoạt Động Hiện Có</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hoạt động gần đây -->
        <div class="card">
            <div class="card-header bg-info text-white text-center">
                Hoạt động gần đây
            </div>
            <div class="card-content p-4">
                @foreach($recentActivities as $activity)
                <div class="activity-item mb-3">
                    <h5>{{ $activity->name }}</h5>
                    <p>Ngày diễn ra: {{ $activity->date->format('d/m/Y') }}</p>
                    <p>Số người tham gia: {{ $activity->registrations_count }}</p>
                </div>
                @endforeach
                <a href="{{ route('admin.activities.index') }}" class="btn btn-primary w-100">Xem tất cả hoạt động</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-white text-center">
                Thông báo từ sinh viên
            </div>
            <div class="card-content p-4">
                @if ($studentIssues->isEmpty())
                <p class="text-center">Không có thông báo từ sinh viên.</p>
                @else
                <ul class="list-group">
                    @foreach ($studentIssues as $issue)
                    <li class="list-group-item d-flex justify-content-between align-items-center
                @if($issue->is_resolved) bg-success text-white @endif">
                        <div>
                            <strong>{{ $issue->student_name }}:</strong> {{ $issue->message }}
                            <br><small>Gửi vào ngày: {{ $issue->created_at->format('d/m/Y H:i') }}</small>
                        </div>

                        @if($issue->is_resolved)
                        <span class="badge badge-success">Đã xử lý</span>
                        @else
                        <a href="{{ route('admin.issues.resolve', $issue->id) }}" class="btn btn-sm btn-warning">Xử lý</a>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @endif
                <a href="{{ route('admin.issues.index') }}" class="btn btn-warning w-100 mt-3">Xem tất cả thông báo</a>
            </div>
        </div>
@endsection