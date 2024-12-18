<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - iPortfolio Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <!-- Vendor CSS Files -->
  <link href="guest/bootstraps/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="guest/bootstraps/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="guest/bootstraps/aos/aos.css" rel="stylesheet">
  <link href="guest/bootstraps/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="guest/bootstraps/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="guest/css/account/user.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header">
    <i class="header-toggle d-xl-none bi bi-list"></i>

    <div class="profile-img">
      <img src="assets/img/my-profile-img.jpg" alt="" class="img-fluid rounded-circle">
    </div>

    <a href="index.html" class="logo">
      <!-- Uncomment the line below if you also wish to use an image logo -->
      <!-- <img src="assets/img/logo.png" alt=""> -->
      <h1 class="sitename">Alex Smith</h1>
    </a>

    <div class="social-links">
      <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
      <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
      <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
      <a href="#" class="google-plus"><i class="bi bi-skype"></i></a>
      <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
    </div>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="#about" class="active"><i class="bi bi-house navicon"></i>HỒ SƠ</a></li>
        <li><a href="#portfolio"><i class="bi bi-person navicon"></i>YÊU CẦU</a></li>
        <li><a href="#contact"><i class="bi bi-images navicon"></i> THÔNG BÁO</a></li>
        <li><a href="{{ route('homepage.index') }}"><i class="bi bi-arrow-left navicon"></i> QUAY LẠI</a></li>
      </ul>
    </nav>
  </header>

  <main class="main">
    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container section-title" data-aos="fade-up">
        <h2>ABOUT</h2>
        <p id="description">Magnam dolores commodi suscipit...</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4 justify-content-center">
          <div class="col-lg-4">
            <img id="profile-img" src="admin/img/p5.jpg" class="img-fluid" alt="">
            <input type="file" id="profile-img-input" accept="image/*" onchange="updateProfileImage()" />
          </div>
          <div class="col-lg-8 content">
            <h2 id="title">Thông tin Khách hàng</h2>
            <p id="summary" class="fst-italic py-3">Lorem ipsum dolor sit amet...</p>
            <div class="row">
              <div class="col-lg-6">
                <ul>
                  <li><i class="bi bi-chevron-right"></i> <strong>Tên Website:</strong> <span id="name">Shopoo</span></li>
                  <li><i class="bi bi-chevron-right"></i> <strong>Ngày tạo:</strong> <span id="day">1 May 1995</span></li>
                  <li><i class="bi bi-chevron-right"></i> <strong>Số điện thoại:</strong> <span id="phone">+123 456 7890</span></li>

                </ul>
              </div>
              <div class="col-lg-6">
                <ul>
                  <li><i class="bi bi-chevron-right"></i> <strong>Địa chỉ:</strong> <span id="address">New York, USA</span></li>
                  <li><i class="bi bi-chevron-right"></i> <strong>Email:</strong> <span id="email">email@example.com</span></li>
                  <!-- <div class="gender-wrapper">
                    <label class="gender-label">Giới tính:</label>
                    <div class="gender-selection">
                      <label class="gender-option">
                        <input type="radio" name="gender" value="male" />
                        <span>Nam</span>
                      </label>
                      <label class="gender-option">
                        <input type="radio" name="gender" value="female" />
                        <span>Nữ</span>
                      </label>
                      <label class="gender-option">
                        <input type="radio" name="gender" value="other" />
                        <span>Khác</span>
                      </label>
                    </div>
                  </div> -->
                </ul>
              </div>
        </div>
      </div>
    </section>
    <!-- /About Section -->

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>YÊU CẦU</h2>
        <p>Quản lý lịch sử đơn hàng</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

          <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
            <li data-filter="*" class="filter-active">All</li>
            <li data-filter=".filter-app">App</li>
            <li data-filter=".filter-product">Product</li>
            <li data-filter=".filter-branding">Branding</li>
            <li data-filter=".filter-books">Books</li>
          </ul><!-- End Portfolio Filters -->



          </div><!-- End Portfolio Container -->

        </div>

      </div>

    </section><!-- /Portfolio Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-5">

            <div class="info-wrap">
              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                <i class="bi bi-geo-alt flex-shrink-0"></i>
                <div>
                  <h3>Address</h3>
                  <p>A108 Adam Street, New York, NY 535022</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                <i class="bi bi-telephone flex-shrink-0"></i>
                <div>
                  <h3>Call Us</h3>
                  <p>+1 5589 55488 55</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                <i class="bi bi-envelope flex-shrink-0"></i>
                <div>
                  <h3>Email Us</h3>
                  <p>info@example.com</p>
                </div>
              </div><!-- End Info Item -->

              <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus" frameborder="0" style="border:0; width: 100%; height: 270px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
          </div>

          <div class="col-lg-7">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4">

                <div class="col-md-6">
                  <label for="name-field" class="pb-2">Your Name</label>
                  <input type="text" name="name" id="name-field" class="form-control" required="">
                </div>

                <div class="col-md-6">
                  <label for="email-field" class="pb-2">Your Email</label>
                  <input type="email" class="form-control" name="email" id="email-field" required="">
                </div>

                <div class="col-md-12">
                  <label for="subject-field" class="pb-2">Subject</label>
                  <input type="text" class="form-control" name="subject" id="subject-field" required="">
                </div>

                <div class="col-md-12">
                  <label for="message-field" class="pb-2">Message</label>
                  <textarea class="form-control" name="message" rows="10" id="message-field" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Send Message</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer position-relative light-background">

    <div class="container">
      <div class="copyright text-center ">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">iPortfolio</strong> <span>All Rights Reserved</span></p>
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a href="https://themewagon.com">ThemeWagon</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->

  <!-- Vendor JS Files -->
  <script src="assets/bootstraps/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/bootstraps/php-email-form/validate.js"></script>
  <script src="assets/bootstraps/aos/aos.js"></script>
  <script src="assets/bootstraps/typed.js/typed.umd.js"></script>
  <script src="assets/bootstraps/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/bootstraps/waypoints/noframework.waypoints.js"></script>
  <script src="assets/bootstraps/glightbox/js/glightbox.min.js"></script>
  <script src="assets/bootstraps/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/bootstraps/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/bootstraps/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
