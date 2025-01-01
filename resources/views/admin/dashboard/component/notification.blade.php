<li class="dropdown">
    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
        <i class="fa fa-envelope"></i>
        <span class="label label-warning">{{ $customerFeedbackCount }}</span>
    </a>
    <ul class="dropdown-menu dropdown-messages">
        @if(isset($customerResponses) && $customerResponses->isNotEmpty())
            @foreach($customerResponses as $response)
                <li>
                    <div class="dropdown-messages-box">
                        <a href="{{ route('request.edit', $response->request_id) }}" class="pull-left">
                            <img alt="image" class="img-circle"
                                 src="{{ $response->customer->profile_image ?? 'default_image.png' }}">
                        </a>
                        <div>
                            <strong>{{ $response->customer->full_name }}</strong> đã phản hồi yêu cầu
                            <strong>{{ $response->request->title ?? 'No title' }}</strong>. <br>
                            <small class="text-muted">{{ $response->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </li>
                <li class="divider"></li>
            @endforeach
        @else
            <li class="text-center">Không có phản hồi nào.</li>
        @endif
        <li>
            <div class="text-center link-block">
                <a href="mailbox.html">
                    <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                </a>
            </div>
        </li>
    </ul>
</li>
