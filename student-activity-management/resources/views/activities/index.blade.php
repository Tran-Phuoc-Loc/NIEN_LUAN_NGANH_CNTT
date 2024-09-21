@extends('layouts.student')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Danh sách các hoạt động</h2>

    @if($activities->isEmpty())
        <div class="alert alert-info">Hiện tại không có hoạt động nào.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped w-100">
                <thead>
                    <tr>
                        <th>Tên hoạt động</th>
                        <th>Ngày diễn ra</th>
                        <th>Hạn đăng ký</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                    <tr>
                        <td>
                            <h5>
                                <a href="{{ route('activities.show', ['id' => $activity->id]) }}">
                                    {{ $activity->name }}
                                </a>
                            </h5>
                        </td>

                        <td>
                            @if($activity->date)
                                {{ $activity->date->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Chưa có thông tin ngày tổ chức</span>
                            @endif
                        </td>

                        <td>
                            @if($activity->registration_end)
                                {{ $activity->registration_end->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Chưa có thông tin đăng ký</span>
                            @endif
                        </td>

                        <td>
                            @if($activity->registration_end && $activity->registration_end < now())
                                <span class="badge bg-danger">Đã hết hạn đăng ký</span>
                            @else
                                <span class="badge bg-success">Còn hạn đăng ký</span>
                            @endif
                        </td>

                        <td>
                            @if($activity->registration_end && $activity->registration_end >= now())
                                <a href="{{ route('registrations.create', ['id' => $activity->id]) }}" class="btn btn-primary">Đăng ký tham gia</a>
                            @else
                                <button class="btn btn-secondary" disabled>Hết hạn đăng ký</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($activities->isNotEmpty())
    <div class="d-flex justify-content-end align-items-center mt-4">
        <p class="text-muted mb-0">
            Tổng số: {{ $activities->count() }} hoạt động
        </p>
    </div>
    @endif
    @endif
</div>

@endsection