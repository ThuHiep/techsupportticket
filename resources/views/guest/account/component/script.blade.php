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

        const submitButton = $("button[type='submit']");

        function checkContent() {
            const content = $('.summernote').summernote('code').trim();
            const isEmpty = $('.summernote').summernote('isEmpty');

            if (isEmpty || content === '' || content === '<p><br></p>') {
                submitButton.prop('disabled', true);
                submitButton.css('opacity', '0.5');
            } else {
                submitButton.prop('disabled', false);
                submitButton.css('opacity', '1');
            }
        }

        checkContent();

        $('.summernote').on('summernote.change', function() {
            checkContent();
        });
    });
</script>