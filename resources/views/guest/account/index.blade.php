<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Index - iPortfolio Bootstrap Template</title>
    <meta content="" name="description" />
    <meta content="" name="keywords" />

    <!-- Vendor CSS Files -->
    <link
      href="guest/bootstraps/bootstrap/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="guest/bootstraps/bootstrap-icons/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link href="guest/bootstraps/bootstrap/aos/aos.css" rel="stylesheet" />
    <link
      href="guest/bootstraps/bootstrap/glightbox/css/glightbox.min.css"
      rel="stylesheet"
    />
    <link
      href="guest/bootstraps/bootstrap/swiper/swiper-bundle.min.css"
      rel="stylesheet"
    />
    <link href="guest/bootstraps/bootstrap/js/main.js" rel="stylesheet" />

    <!-- Main CSS File -->
    <link href="guest/css/account/user.css" rel="stylesheet" />
  </head>

  <body class="index-page">
    <header id="header" class="header">
      <i class="header-toggle d-xl-none bi bi-list"></i>

      <div class="profile-img">
        <img
          src="/admin/img/a2.jpg"
          alt=""
          class="img-fluid rounded-circle"
        />
      </div>

      <a href="index.html" class="logo">
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
          <li>
            <a href="#home"><i class="bi bi-house navicon"></i>TRANG CHỦ</a>
          </li>
          <li>
            <a href="#about"><i class="bi bi-person navicon"></i>HỒ SƠ</a>
          </li>
          <li>
            <a href="#portfolio"
              ><i class="bi bi-card-text navicon"></i>YÊU CẦU</a
            >
          </li>
          <li>
            <a href="#contact"><i class="bi bi-gear navicon"></i>CÀI ĐẶT</a>
          </li>
          <li><a href="{{ route('homepage.index') }}"><i class="bi bi-arrow-left navicon"></i> QUAY LẠI</a></li>
        </ul>
      </nav>
    </header>

    <main class="main">
      <!-- TRANG CHỦ -->
      <section id="home" class="home section">
        <div class="container" data-aos="fade-up">
          <h1>TRANG CHỦ</h1>
          <div class="row">
            <div class="col-lg-4 text-center">
              <img
                src="assets/img/icon-doc.png"
                alt="icon-doc"
                class="img-fluid"
              />
              <p>Yêu cầu đang chờ</p>
              <h2>0</h2>
              <a href="#">Xem</a>
            </div>
            <div class="col-lg-8 text-center">
              <img
                src="assets/img/favicon.png"
                alt="support"
                class="img-fluid"
              />
              <p>Bạn đang không có yêu cầu hỗ trợ nào!</p>
              <button class="btn btn-success">Tạo yêu cầu hỗ trợ</button>
            </div>
          </div>
          <div class="text-center mt-4"></div>
        </div>
      </section>

      <!-- About Section -->
      <section id="about" class="about section">
        <div class="container section-title" data-aos="fade-up">
          <h1>HỒ SƠ</h1>
          <p id="description">Magnam dolores commodi suscipit...</p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="row gy-4 justify-content-center">
            <div class="col-lg-4">
              <img
                id="profile-img"
                src="assets/img/my-profile-img.jpg"
                class="img-fluid"
                alt=""
              />
            </div>
            <div class="col-lg-8 content">
              <h2 id="title">Thông tin Khách hàng</h2>
              <p id="summary" class="fst-italic py-3">
                Lorem ipsum dolor sit amet...
              </p>
              <div class="row">
                <div class="col-lg-6">
                  <ul>
                    <li>
                      <i class="bi bi-chevron-right"></i>
                      <strong>Họ và tên:</strong>
                      <span id="name">Alex Smith</span>
                    </li>
                    <li>
                      <i class="bi bi-chevron-right"></i>
                      <strong>Năm sinh:</strong>
                      <span id="day">1 May 1995</span>
                    </li>
                    <li>
                      <i class="bi bi-chevron-right"></i>
                      <strong>Giới tính:</strong> <span id="phone">Nam</span>
                    </li>
                    <li>
                      <i class="bi bi-chevron-right"></i>
                      <strong>Số điện thoại:</strong>
                      <span id="name">01235123</span>
                    </li>
                    <li>
                      <i class="bi bi-chevron-right"></i>
                      <strong>Địa chỉ:</strong> <span id="day">Nha Trang</span>
                    </li>
                  </ul>
                </div>
                <div class="col-lg-6">
                  <ul>
                    <li>
                      <i class="bi bi-chevron-right"></i>
                      <strong>Công ty:</strong>
                      <span id="phone">SweetSoft</span>
                    </li>
                    <li>
                      <i class="bi bi-chevron-right"></i>
                      <strong>Email:</strong>
                      <span id="address">abc@gmail.com</span>
                    </li>
                    <li>
                      <i class="bi bi-chevron-right"></i> <strong>TAX:</strong>
                      <span id="email">123456</span>
                    </li>
                    <li>
                      <i class="bi bi-chevron-right"></i>
                      <strong>Website:</strong> <span id="name">Shopoo</span>
                    </li>
                    <li>
                      <i class="bi bi-chevron-right"></i>
                      <strong>Số điện thoại:</strong>
                      <span id="phone">+123 456 7890</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- CHỈNH SỬA THÔNG TIN KHÁCH HÀNG -->
            <section id="about" class="about section">
              <div class="img-container">
                <button id="edit-btn" class="edit-button">
                  CHỈNH SỬA THÔNG TIN
                </button>
              </div>
            </section>

            <!-- Modal -->
            <div id="editModal" class="modal">
              <div class="modal-content">
                <span class="close">&times;</span>
                <h1 class="modal-title">Chỉnh sửa thông tin khách hàng</h1>
                <form>
                  <div class="form-container">
                    <!-- Cột 1 -->
                    <div class="form-column">
                      <div class="form-group">
                        <label for="username">Họ và Tên*</label>
                        <input type="text" id="username" value="Alex Smith">
                      </div>
                      <div class="form-group">
                        <label for="dob">Năm sinh*</label>
                        <input type="date" id="dob">
                      </div>
                      <div class="form-group">
                        <label for="gender">Giới tính*</label>
                        <select id="gender">
                          <option value="Nam" selected>Nam</option>
                          <option value="Nữ">Nữ</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="phone">Số điện thoại*</label>
                        <input type="text" id="phone" value="0794599581">
                      </div>
                      <div class="form-group">
                        <label for="customer-name">Địa chỉ</label>
                        <input type="text" id="customer-name" value="Trần Hàng Tổng Đạt">
                      </div>
                   
      
                    </div>
            
                    <!-- Cột 2 -->
                    <div class="form-column">
                      <div class="form-group">
                        <label for="customer-name">Công Ty</label>
                        <input type="text" id="customer-name" value="Trần Hàng Tổng Đạt">
                      </div>
                      <div class="form-group">
                        <label for="customer-name">Email*</label>
                        <input type="text" id="customer-name" value="Trần Hàng Tổng Đạt">
                      </div>
                      <div class="form-group">
                        <label for="customer-name">TAX</label>
                        <input type="text" id="customer-name" value="Trần Hàng Tổng Đạt">
                      </div>
                      <div class="form-group">
                        <label for="email">Website</label>
                        <input type="email" id="email" value="kari87@gmail.com">
                      </div>
                      <div class="form-group">
                        <label for="upload-image">Ảnh đại diện</label>
                        <input type="file" id="upload-image" accept="image/*">
                        
                      </div>
                    </div>
                  </div>
            
                  <!-- Buttons -->
                  <div class="button-group">
                    <button type="submit" class="btn-update">Cập nhật</button>
                    <button type="button" class="btn-cancel">Hủy</button>
                  </div>
                </form>
              </div>
            </div>
            
            <script>
              // Get modal and button elements
              const modal = document.getElementById("editModal");
              const btn = document.getElementById("edit-btn");
              const closeBtn = document.querySelector(".close");
              const cancelBtn = document.querySelector(".btn-cancel");

              // Open modal when button is clicked
              btn.onclick = () => {
                modal.style.display = "block";
              };

              // Close modal when 'x' button is clicked
              closeBtn.onclick = () => {
                modal.style.display = "none";
              };
              // Đóng modal khi nhấn nút "HỦY"
              cancelBtn.onclick = () => {
                modal.style.display = "none";
              };

              // Close modal when clicking outside content
              window.onclick = (event) => {
                if (event.target === modal) {
                  modal.style.display = "none";
                }
              };
            </script>
          </div>
        </div>
      </section>

      <!-- /About Section -->

      <!-- Portfolio Section -->
      <section id="portfolio" class="portfolio section light-background">
        <div class="container section-title" data-aos="fade-up">
          <h1>YÊU CẦU</h1>
          <h3>LỊCH SỬ YÊU CẦU</h3>
          <h3></h3>
          <div class="container">
          <div
            class="isotope-layout"
            data-default-filter="*"
            data-layout="masonry"
            data-sort="original-order"
          >
            <ul
              class="portfolio-filters isotope-filters"
              data-aos="fade-up"
              data-aos-delay="100"
            >
              <li data-filter="*" class="filter-active">Tất cả</li>
              <li data-filter=".filter-app">ĐANG XỬ LÝ</li>
              <li data-filter=".filter-product">ĐÃ XỬ LÝ</li>
              <li data-filter=".filter-branding">ĐÃ HỦY</li>
            </ul>
          </div>
        </div>
      </section>
        </div>

        
      <!-- /Portfolio Section -->

      <!-- Lịch sử yêu cầu -->
      <section id="request-history" class="request-history section">
        <div class="container">
          <!-- Thẻ yêu cầu -->
          <div class="request-item" onclick="viewRequestDetail('YC003')">
            <div class="request-info">
              <h3>YC003</h3>
              <span class="status deployed">Đang chờ xử lý</span>
              <p>Lỗi tài khoản admin</p>
            </div>
            <div class="request-arrow">→</div>
          </div>

          <div class="request-item" onclick="viewRequestDetail('YC002')">
            <div class="request-info">
              <h3>YC002</h3>
              <span class="status completed">Đã xử lý</span>
              <p>Không truy cập được trang web</p>
            </div>
            <div class="request-arrow">→</div>
          </div>

          <div class="request-item" onclick="viewRequestDetail('YC001')">
            <div class="request-info">
              <h3>YC001</h3>
              <span class="status completed">Đã xử lý</span>
              <p>Không hiển thị form đăng nhập</p>
            </div>
            <div class="request-arrow">→</div>
          </div>

          <div class="request-item" onclick="viewRequestDetail('YC004')">
            <div class="request-info">
              <h3>YC004</h3>
              <span class="status canceled">Đã hủy</span>
              <p>Hủy yêu cầu xử lý dữ liệu</p>
            </div>
            <div class="request-arrow">→</div>
          </div>
        </div>
        <script>
          document.addEventListener("DOMContentLoaded", function () {
            // Lấy tất cả các mục lọc và các thẻ yêu cầu
            const filters = document.querySelectorAll(".portfolio-filters li");
            const requestItems = document.querySelectorAll(".request-item");

            // Hàm lọc yêu cầu với hiệu ứng đẩy lên trên
            function filterRequests(status) {
              const visibleItems = []; // Mảng chứa các thẻ yêu cầu phù hợp
              const hiddenItems = []; // Mảng chứa các thẻ yêu cầu không phù hợp

              // Phân loại các yêu cầu thành hiển thị và ẩn
              requestItems.forEach((item) => {
                const itemStatus = item.querySelector(".status").classList;

                if (status === "all" || itemStatus.contains(status)) {
                  visibleItems.push(item); // Thêm vào mảng hiển thị
                } else {
                  hiddenItems.push(item); // Thêm vào mảng ẩn
                }
              });

              // Hiển thị các yêu cầu phù hợp
              visibleItems.forEach((item) => {
                item.style.display = "flex"; // Hiển thị
                item.classList.add("show");
                item.classList.remove("hide");
              });

              // Ẩn các yêu cầu không phù hợp
              hiddenItems.forEach((item) => {
                item.style.display = "none"; // Ẩn đi
                item.classList.add("hide");
                item.classList.remove("show");
              });
            }

            // Gắn sự kiện click cho các mục lọc
            filters.forEach((filter) => {
              filter.addEventListener("click", function () {
                // Xóa lớp active trên tất cả các mục lọc
                filters.forEach((f) => f.classList.remove("filter-active"));
                filter.classList.add("filter-active");

                // Lấy trạng thái tương ứng dựa trên text
                const filterText = filter.textContent.trim();
                if (filterText === "Tất cả") {
                  filterRequests("all");
                } else if (filterText === "ĐANG XỬ LÝ") {
                  filterRequests("deployed");
                } else if (filterText === "ĐÃ XỬ LÝ") {
                  filterRequests("completed");
                } else if (filterText === "ĐÃ HỦY") {
                  filterRequests("canceled");
                }
              });
            });
          });
        </script>
      </section>

      <!-- Contact Section -->
      <section id="contact" class="contact section">
        <div class="container section-title" data-aos="fade-up">
          <h1>CÀI ĐẶT</h1>
        </div>
      
        <!-- Cards Section -->
        <div class="card-container">
          <!-- Card 1: YÊU CẦU XÓA TÀI KHOẢN -->
          <div class="card" id="delete-account-card">
            <h2>YÊU CẦU XÓA TÀI KHOẢN</h2>
          </div>
      
          <!-- Card 2: CHUYỂN ĐỔI TÀI KHOẢN -->
          <div class="card" id="switch-account-card">
            <h2>CHUYỂN ĐỔI TÀI KHOẢN</h2>
          </div>
        </div>
      </section>
      
      <!-- Modal Setting 1: YÊU CẦU XÓA TÀI KHOẢN -->
      <div id="modal-setting-delete" class="modal-setting">
        <div class="modal-setting-content">
          <span class="close-setting close-delete">&times;</span>
          <h2>YÊU CẦU XÓA TÀI KHOẢN</h2>
          <p>Bạn có chắc chắn muốn xóa tài khoản này không?</p>
          <button class="btn-setting-confirm">Xác nhận</button>
          <button class="btn-setting-cancel">Hủy</button>
        </div>
      </div>
      
      <!-- Modal Setting 2: CHUYỂN ĐỔI TÀI KHOẢN -->
      <div id="modal-setting-switch" class="modal-setting">
        <div class="modal-setting-content">
          <span class="close-setting close-switch">&times;</span>
          <h2>CHUYỂN ĐỔI TÀI KHOẢN</h2>
          <p>Vui lòng chọn tài khoản bạn muốn chuyển đổi sang.</p>
          <button class="btn-setting-confirm">Chuyển đổi</button>
          <button class="btn-setting-cancel">Hủy</button>
        </div>
      </div>

      <script>// Lấy các phần tử modal-setting
        const modalDelete = document.getElementById('modal-setting-delete');
        const modalSwitch = document.getElementById('modal-setting-switch');
        
        const deleteCard = document.getElementById('delete-account-card');
        const switchCard = document.getElementById('switch-account-card');
        
        const closeDelete = document.querySelector('.close-delete');
        const closeSwitch = document.querySelector('.close-switch');
        
        // Hiển thị modal-setting xóa tài khoản
        deleteCard.onclick = () => {
          modalDelete.style.display = 'block';
        };
        
        // Hiển thị modal-setting chuyển đổi tài khoản
        switchCard.onclick = () => {
          modalSwitch.style.display = 'block';
        };
        
        // Đóng modal-setting xóa tài khoản
        closeDelete.onclick = () => {
          modalDelete.style.display = 'none';
        };
        
        // Đóng modal-setting chuyển đổi tài khoản
        closeSwitch.onclick = () => {
          modalSwitch.style.display = 'none';
        };
        
        // Đóng modal-setting khi nhấn bên ngoài
        window.onclick = (event) => {
          if (event.target === modalDelete) {
            modalDelete.style.display = 'none';
          }
          if (event.target === modalSwitch) {
            modalSwitch.style.display = 'none';
          }
        };
        </script>