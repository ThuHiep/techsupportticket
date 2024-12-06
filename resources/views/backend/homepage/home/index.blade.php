<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Trang chủ SWEETSOFT</title>
    <link
        rel="stylesheet"
        type="text/css"
        href="{{ asset('Content/assets/styles/main_styles.css') }}"
    />

{{--    <link--}}
{{--    rel="stylesheet"--}}
{{--    type="text/css"--}}
{{--    href="{{aContent/assets/styles/head.css"--}}
{{--/>--}}
{{--    <link--}}
{{--        rel="stylesheet"--}}
{{--        type="text/css"--}}
{{--        href="<?php echo $base_url; ?>/Content/assets/styles/search_box.css"--}}
{{--    />--}}
{{--    <link href="<?php echo $base_url; ?>/css/main.css" rel="stylesheet" />--}}
{{--    <link--}}
{{--        href="<?php echo $base_url; ?>/css/bootstrap.min.css"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
{{--    <link--}}
{{--        href="<?php echo $base_url; ?>/Content/assets/plugins/OwlCarousel2-2.2.1/animate.css"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
{{--    <link--}}
{{--        href="<?php echo $base_url; ?>/Content/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.css"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
{{--    <link--}}
{{--        href="<?php echo $base_url; ?>/Content/assets/plugins/OwlCarousel2-2.2.1/owl.theme.default.css"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
{{--    <link--}}
{{--        href="<?php echo $base_url; ?>/Content/assets/styles/responsive.css"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
{{--    <link--}}
{{--        href="<?php echo $base_url; ?>/Content/assets/styles/bootstrap4/bootstrap.min.css"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
{{--    <link--}}
{{--        href="<?php echo $base_url; ?>/Content/assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
{{--    <link rel="preconnect" href="https://fonts.googleapis.com" />--}}
{{--    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />--}}
{{--    <link--}}
{{--        href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
</head>
{{--<body>--}}
{{--<!--container-->--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Support</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<main>
    <section class="welcome-section">
        <div class="left-image">
            <img src="{{asset('Images/trangchu.png')}}" />
        </div>
        <div class="right-container">
            <div class="right-content">
                <h1>
                    Welcome to <br />
                    Customer Support
                </h1>
                <div class="search-bar">
                    <input type="text" placeholder="Search Support" />
                    <button>
                        <img
{{--                            src="<?php echo $base_url; ?>/Images/search.png"--}}
                            alt="Search"
                        />
                    </button>
                </div>
            </div>
        </div>
    </section>
    <!-- Container for quick links below the welcome section -->
    <section class="quick-links">
        <h2>Quick Links</h2>
        <div class="quick-links-container">
            <a href="#form-section" class="box">
{{--                <img src="<?php echo $base_url; ?>/Images/form.png" alt="Form" />--}}
                <p>Form</p>
            </a>
            <a href="#faq" class="box">
{{--                <img src="<?php echo $base_url; ?>/Images/faq.png" alt="FAQ" />--}}
                <p>FAQ</p>
            </a>
            <a href="#support-section" class="box">
{{--                <img src="<?php echo $base_url; ?>/Images/support.png" alt="Support" />--}}
                <p>Support</p>
            </a>
        </div>
    </section>
    <!-- Divider line -->
    <hr class="section-divider" />

    <!-- Container for FAQ list -->
    <section class="faq-section">
        <h1 id ="faq"> FAQ</h1>
        <ul class="faq-list">
            <li><a href="#">Thời gian phản hồi từ đội hỗ trợ là bao lâu?</a></li>
            <li>
                <a href="#">Tôi quên mật khẩu đăng nhập vào trang quản trị website, làm sao để khôi phục?</a>
            </li>
            <li><a href="#">Website của tôi bị hack, tôi cần làm gì?</a></li>
            <li>
                <a href="#"
                >Tôi muốn cập nhật nội dung trên website, tôi phải làm thế nào?</a
                >
            </li>
            <li>
                <a href="#"
                >Website có thể chịu được bao nhiêu lượt truy cập cùng lúc?</a
                >
            </li>
            <li>
                <a href="#"
                >Website của tôi không hiển thị đúng trên thiết bị di động, làm sao khắc phục?</a
                >
            </li>
            <li>
                <a href="#"
                >Tôi muốn thêm tính năng mới vào website, liệu có khả thi không?</a
                >
            </li>
            <li>
                <a href="#"
                >Dữ liệu trên website của tôi có được sao lưu không?</a
                >
            </li>
        </ul>
        <h1> Account Support</h1>
        <ul class="faq-list">
            <li>
                <a href="#"
                >Tôi quên mật khẩu, làm thế nào để khôi phục tài khoản?</a
                >
            </li>
            <li>
                <a href="#"
                >Tại sao tài khoản của tôi bị khóa?</a
                >
            </li>
        </ul>
    </section>
</main>
</body>
</html>

{{--<script src="<?php echo $base_url; ?>/Content/assets/plugins/OwlCarousel2-2.2.1/owl.carousel.js"></script>--}}
{{--<script src="<?php echo $base_url; ?>/Content/assets/plugins/easing/easing.js"></script>--}}
{{--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>--}}
{{--<script src="<?php echo $base_url; ?>/css/popper.min.js"></script>--}}
{{--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>--}}
{{--<script src="<?php echo $base_url; ?>/Content/assets/js/jquery-3.2.1.min.js"></script>--}}
</body>
</html>
