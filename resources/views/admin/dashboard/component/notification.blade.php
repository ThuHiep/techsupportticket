<li class="dropdown">
    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
        <i class="fa fa-envelope"></i>
        <span class="label label-warning">{{ $unreadRequestCount }}</span>
    </a>
    <ul class="dropdown-menu dropdown-messages">
        @forelse($unreadRequests as $request)
        <li onclick="window.location='{{ route('request.edit', $request->request_id) }}'" style="cursor: pointer;">
            <div class="dropdown-messages-box">
                <a href="{{ route('request.edit', $request->request_id) }}" class="pull-left">
                    <img alt="image" class="img-circle" src="{{$request->customer->profile_image ? asset('admin/img/customer/' .  $request->customer->profile_image) : asset('admin/img/customer/default.png') }}" alt="">
                </a>
                <div>
                    <strong>{{ $request->customer->full_name ?? 'Khách hàng' }}</strong> đã gửi
                    <strong>{{ $request->feedback_count }}</strong> phản hồi vào yêu cầu
                    <strong>"{{ $request->subject }}"</strong>. <br>
                    <small class="text-muted"> Thời gian: {{ $request->last_feedback_time->format('H:i d/m/Y')}}</small>
                </div>
            </div>
        </li>
        <li class="divider"></li>
        @empty
        <div class="text-center link-block">
            <strong>Không có phản hồi</strong>
        </div>
        @endforelse
    </ul>
</li>