@ -1,201 +0,0 @@
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Trang chủ SWEETSOFT</title>

    <link rel="stylesheet" href="backend/css/homepage/head.css?v=1">
    <link rel="stylesheet" href="backend/css/homepage/home.css?v=1">
    <link rel="stylesheet" href="backend/css/homepage/main.css?v=1">
    <link rel="stylesheet" href="backend/css/homepage/bootstrap.min.css?v=1">
    <link
      href="backend/css/homepage/bootstrap.min.css"
      rel="stylesheet"
    />
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <body>
      <script>
        document.addEventListener("DOMContentLoaded", function () {
          const menuIcon = document.querySelector(".menu-icon");
          const navMenu = document.querySelector(".nav-menu");
  
          menuIcon.addEventListener("click", function () {
            navMenu.classList.toggle("active");
          });
        });
      </script>
    </head>
    <body>
      <body>
        <header>
          <div class="logo">
            <img src="backend/img/homepage/logosweetsoft.png" alt="Form">
          </div>
          <div class="account-section">
            <a href="#store">
              <i class="fas fa-store icon"></i>Home
            </a>
            <a href="#order">
              <i class="fas fa-truck icon"></i>FAQ
            </a>
            <a href="#cart">
              <i class="fas fa-shopping-bag icon"></i>Contact Us
            </a>
            <a href="#account-but" class="acc-but">
              <p>Đăng nhập</p>
            </a>
          </div>
        </header>
      </body>
    <!--container-->
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Customer Support</title>
      </head>
      <body>
        <main>
          <section class="welcome-section">
            <div class="left-image">
              <img src="backend/img/homepage/trangchu.png" alt="Trang Chủ">
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
                    <img src="backend/img/homepage/search.png" alt="Form">
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
                <img src="backend/img/homepage/form.png" alt="Form">
                <p>Form</p>
              </a>
              <a href="#faq" class="box">
                <img src="backend/img/homepage/faq.png" alt="FAQ">
                <p>FAQ</p>
              </a>
              <a href="#support-section" class="box">
                <img src="backend/img/homepage/support.png" alt="Support">
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
          <!-- Footer -->
          <footer class="footer">
            <div class="footer-logo">
              <img src="backend/img/homepage/logosweetsoft.png" alt="Logo Foot">
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
                  <img src="backend/img/homepage/logosweetsoft.png" alt="FB">
                </div>
              </div>
            </div>
            <div class="footer-bottom">
              <p>© 2024 Công Ty Cổ Phần SweetSoft. Điều khoản & điều kiện | Chính sách bảo mật | Sơ đồ</p>
            </div>
          </footer>
          
        </main>
      </body>ông 
    </html>
  </body>
</html>