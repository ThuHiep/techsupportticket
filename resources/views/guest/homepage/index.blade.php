<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Trang chủ SWEETSOFT</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/guest/css/homepage/home.css?v=1">
    <link rel="stylesheet" href="/guest/css/homepage/head.css?v=1">
    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet" />
</head>

<!-- Header -->
<body>
    <header>
        <div class="logo">
            <img src="/guest/img/logosweetsoft.png" alt="Logo SweetSoft">
        </div>
        <div class="account-section">
            <a href="#store"><i class="fas fa-store icon"></i>Home</a>
            <a href="#order"><i class="fas fa-truck icon"></i>FAQ</a>
            <a href="#cart"><i class="fas fa-shopping-bag icon"></i>Contact Us</a>
            <a href="#account-but" class="acc-but">
                <p>Đăng nhập</p>
            </a>
        </div>
    </header>

<!-- Welcome Section -->
    <main>
        <section class="welcome-section">
            <div class="left-image">
                <img src="/guest/img/trangchu.png" alt="Trang Chủ">
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
                            <img src="/guest/img/search.png" alt="Search Icon">
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Links Section -->
        <section class="quick-links">
            <h2>Quick Links</h2>
            <div class="quick-links-container">
                <a href="#form-section" class="box">
                    <img src="/guest/img/form.png" alt="Form">
                    <p>Form</p>
                </a>
                <a href="#faq" class="box">
                    <img src="/guest/img/faq.png" alt="FAQ">
                    <p>FAQ</p>
                </a>
                <a href="#support-section" class="box">
                    <img src="/guest/img/support.png" alt="Support">
                    <p>Support</p>
                </a>
            </div>
        </section>

        <!-- Divider line -->
        <hr class="section-divider" />

        <!-- FAQ Section -->
        <section class="faq-section">
            <h1 id="faq">FAQ</h1>
            <ul class="faq-list">
                <li><a href="#">Thời gian phản hồi từ đội hỗ trợ là bao lâu?</a></li>
                <li><a href="#">Tôi quên mật khẩu đăng nhập vào trang quản trị website, làm sao để khôi phục?</a></li>
                <li><a href="#">Website của tôi bị hack, tôi cần làm gì?</a></li>
                <li><a href="#">Tôi muốn cập nhật nội dung trên website, tôi phải làm thế nào?</a></li>

            </ul>
  <!-- New Button Section -->
  <button id="ask-question-button">Đặt câu hỏi</button>
<div id="question-form" style="display: none; margin-top: 20px;">
    <textarea id="question-text" placeholder="Nhập câu hỏi của bạn" rows="5" style="width: 100%; padding: 10px;"></textarea>
    <button id="submit-question-button" style="margin-top: 10px;">Gửi câu hỏi</button>
</div>

            <h1>Account Support</h1>
            <ul class="faq-list">
                <li><a href="#">Tôi quên mật khẩu, làm thế nào để khôi phục tài khoản?</a></li>
                <li><a href="#">Tại sao tài khoản của tôi bị khóa?</a></li>
            </ul>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-logo">
            <img src="/guest/img/logosweetsoft.png" alt="Logo Footer">
        </div>
        <div class="footer-content">
            <div class="footer-section contact-info">
                <h4>Liên hệ</h4>
                <p>Văn phòng: Ô 10 Tầng 12A - Tòa nhà VCN Tower, 02 Tố Hữu, khu đô thị VCN, P.Phước Hải, Tp Nha Trang, tỉnh Khánh Hòa, Việt Nam</p>
                <p>Email: info@sweetsoft.vn</p>
                <p>Điện thoại: 0258.3704199 - 0258.6567900</p>
                <p>Thứ hai - Thứ sáu 7:30–17:00 Thứ bảy 7:30–11:30 <br> Cuối tuần nghỉ</p>
            </div>
            <div class="footer-section services">
                <h4>Dịch vụ của chúng tôi</h4>
                <ul>
                    <li>Thiết kế web</li>
                    <li>Thuê hosting</li>
                    <li>Đăng ký tên miền</li>
                    <li>Phát triển phần mềm</li>
                    <li>Dịch vụ trực tuyến</li>
                </ul>
            </div>
            <div class="footer-section facebook">
                <h4>SweetSoft trên Facebook</h4>
                <div class="facebook-widget">
                    <img src="/guest/img/logosweetsoft.png" alt="Facebook Logo">
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2024 Công Ty Cổ Phần SweetSoft. Điều khoản & điều kiện | Chính sách bảo mật | Sơ đồ</p>
        </div>
    </footer>

    <!-- Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuIcon = document.querySelector(".menu-icon");
            const navMenu = document.querySelector(".nav-menu");
            menuIcon.addEventListener("click", function() {
                navMenu.classList.toggle("active");
            });
        });
    </script>
</body>

</html>
