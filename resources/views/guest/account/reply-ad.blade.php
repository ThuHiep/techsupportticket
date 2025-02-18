<!DOCTYPE html>
@include('guest.account.component.header')
<html>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<script src="{{ asset('admin/js/plugins/summernote/summernote.min.js') }}"></script>

<body>
    <div class="animated fadeInRight">
        <div class="mail-box-header">
            <h2>
                Soạn nội dung phản hồi
            </h2>
        </div>
        <div class="mail-box">
            <form action="{{ route('customer.reply', $request->request_id) }}" method="POST">
                @csrf
                <div class="mail-text">
                    <textarea id="reply_content-{{$request->request_id}}"
                        name="reply_content"
                        class="summernote"
                        data-request-id="{{$request->request_id}}"></textarea>
                    <div class="clearfix"></div>
                </div>
                <div class="mail-body text-right tooltip-demo">
                    <button type="submit" id="btn-feedback-{{$request->request_id}}"
                        class="btn btn-sm btn-primary"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Send"
                        disabled>
                        <i class="fa fa-share"></i>
                        <span style="font-size: 18px">Gửi</span>
                    </button>
                </div>
            </form>
            <div class="clearfix"></div>

        </div>
    </div>

    @include('guest.account.component.script')


</body>

</html>