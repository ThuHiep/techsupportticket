
    <!-- Mainly scripts -->
    <script src="backend/js/jquery-3.1.1.min.js"></script>
    <script src="backend/js/bootstrap.min.js"></script>
    <script src="backend/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="backend/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>


    <!-- Đoạn mã JavaScript bạn muốn thêm -->
    <script>
        $(document).ready(function () {
            $('#side-menu').metisMenu({
                toggle: false // Tắt tính năng tự động đóng các dropdown khác
            });
        });
    </script>
    <!--Đoạn gốc ban đầu-->
     @if(isset($config['js']) && is_array($config['js']))
        @foreach($config['js'] as $key => $val)

        {!! '<script src = "'.$val.'"></script>'!!}

        @endforeach
     @endif
