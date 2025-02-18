<!-- Mainly scripts -->
<script src="{{ asset('admin/js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('admin/js/plugins/chartJs/Chart.min.js') }}"></script>
<script src="{{ asset('admin/js/demo/chartjs-demo.js') }}"></script>

<script src="{{ asset('admin/js/plugins/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('admin/js/plugins/flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('admin/js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
<script src="{{ asset('admin/js/plugins/flot/jquery.flot.spline.js') }}"></script>
<script src="{{ asset('admin/js/plugins/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('admin/js/demo/peity-demo.js') }}"></script>
<script src="{{ asset('admin/js/plugins/flot/jquery.flot.symbol.js') }}"></script>
<script src="{{ asset('admin/js/plugins/flot/jquery.flot.time.js') }}"></script>
<script src="{{ asset('admin/js/plugins/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('admin/js/inspinia.js') }}"></script>
<script src="{{ asset('admin/js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('admin/js/plugins/pace/pace.min.js') }}"></script>
<script src="{{ asset('admin/js/plugins/summernote/summernote.min.js') }}"></script>

<!-- SUMMERNOTE -->

<script>
    $(document).ready(function() {

        $('.summernote').summernote();

    });
</script>


<!--Đoạn gốc ban đầu-->
@if(isset($config['js']) && is_array($config['js']))
@foreach($config['js'] as $key => $val)

{!! '<script src="'.$val.'"></script>'!!}

@endforeach
@endif