<!-- Mainly scripts -->
<script src="admin/js/jquery-3.1.1.min.js"></script>
<script src="admin/js/bootstrap.min.js"></script>
<script src="admin/js/plugins/iCheck/icheck.min.js"></script>
<script src="admin/js/plugins/pace/pace.min.js"></script>
<script src="admin/js/plugins/summernote/summernote.min.js"></script>

<!-- SUMMERNOTE -->


<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200
        });

        function checkContent(requestId) {
            const content = $(`#reply_content-${requestId}`).summernote('code').trim();
            const isEmpty = $(`#reply_content-${requestId}`).summernote('isEmpty');
            const submitButton = $(`#btn-feedback-${requestId}`);

            if (isEmpty || content === '' || content === '<p><br></p>') {
                submitButton.prop('disabled', true);
                submitButton.css('opacity', '0.5');
            } else {
                submitButton.prop('disabled', false);
                submitButton.css('opacity', '1');
            }
        }

        $('.summernote').each(function() {
            const requestId = $(this).data('request-id');
            checkContent(requestId);

            $(this).on('summernote.change', function() {
                checkContent(requestId);
            });
        });
    });
</script>