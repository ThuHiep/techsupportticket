<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Tài khoản</title>
  <meta content="" name="description" />
  <meta content="" name="keywords" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link
    href="guest/bootstraps/bootstrap/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="guest/bootstraps/bootstrap-icons/bootstrap-icons.css"
    rel="stylesheet" />
  <link href="guest/bootstraps/bootstrap/aos/aos.css" rel="stylesheet" />
  <link
    href="guest/bootstraps/bootstrap/glightbox/css/glightbox.min.css"
    rel="stylesheet" />
  <link
    href="guest/bootstraps/bootstrap/swiper/swiper-bundle.min.css"
    rel="stylesheet" />
  <link href="guest/bootstraps/bootstrap/js/main.js" rel="stylesheet" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <!-- Main CSS File -->
  <link href="guest/css/account/user.css" rel="stylesheet" />

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* Nút mở modal */
    .btn-open-modal {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      align-items: center;
    }

    .btn-open-modal:hover {
      background-color: #0056b3;
    }

    /* Modal */
    .modal-switch {
      display: none;
      /* Ẩn mặc định */
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
    }

    /* Nội dung modal */
    .modal-content-switch {
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      border-radius: 10px;
      width: 40%;
      position: relative;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Nút đóng modal */
    .close {
      position: absolute;
      top: 10px;
      right: 20px;
      color: #aaa;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover,
    .close:focus {
      color: black;
    }

    /* Danh sách tài khoản */
    .account-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .account-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }

    .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 15px;
    }

    .account-name {
      flex: 1;
      font-size: 16px;
    }

    .btn-switch {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
    }

    .btn-switch:hover {
      background-color: #218838;
    }
  </style>
</head>

<body class="index-page">
  <header id="header" class="header">
    <i class="header-toggle d-xl-none bi bi-list"></i>

    <div class="profile-img">
      <img
        src="{{$logged_user->profile_image ? asset('admin/img/customer/' .  $logged_user->profile_image) : asset('admin/img/customer/default.png') }}"
        alt=""
        class="img-fluid rounded-circle" />
    </div>

    <a href="index.html" class="logo">
      <h1 class="sitename">{{$logged_user->full_name}}</h1>
    </a>


    <nav id="navmenu" class="navmenu">
      <ul>
        <li>
          <a href="#home"><i class="bi bi-house navicon"></i>TRANG CHỦ</a>
        </li>
        <li>
          <a href="#about"><i class="bi bi-person navicon"></i>HỒ SƠ</a>
        </li>
        <li>
          <a href="#portfolio"><i class="bi bi-card-text navicon"></i>YÊU CẦU</a>
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
          <div class="col-lg-6 text-center">
            <img src="/guest/img/hourglass.gif" alt="support"
              class="img-fluid-home" />
            <p>Yêu cầu đang chờ xử lý</p>
            <h2>0</h2>
            <a href="#portfolio">Xem</a>
          </div>
          <div class="col-lg-6 text-center">
            <img src="/guest/img/notification.gif" alt="support"
              class="img-fluid-home" />
            <p>Hôm nay bạn không có yêu cầu hỗ trợ nào!</p>
            <button class="btn btn-success" onclick="window.location.href='{{ route('showFormRequest') }}'">Tạo yêu cầu hỗ trợ</button>
          </div>
        </div>
        <div class="text-center mt-4"></div>
      </div>
    </section>


    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container section-title" data-aos="fade-up">
        <h1>HỒ SƠ</h1>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4 justify-content-center">
          <div class="col-lg-4" style="margin-top: 40px">
            <img
              id="profile-img"
              src="{{$logged_user->profile_image ? asset('admin/img/customer/' .  $logged_user->profile_image) : asset('admin/img/customer/default.png') }}"
              class="img-fluid"
              alt="" style="width: 250px" />
          </div>
          <div class="col-lg-8 content" style="margin-top: 40px">
            <h2 id="title">Thông tin cá nhân</h2>
            <div class="row">
              <div class="col-lg-6">
                <ul>
                  <li>
                    <i class="bi bi-chevron-right"></i>
                    <strong>Họ và tên:</strong>
                    <span id="name">{{$logged_user->full_name}}</span>
                  </li>
                  <li>
                    <i class="bi bi-chevron-right"></i>
                    <strong>Năm sinh:</strong>
                    <span id="day">{{$logged_user->date_of_birth->format('d/m/Y')}}</span>
                  </li>
                  <li>
                    <i class="bi bi-chevron-right"></i>
                    <strong>Giới tính:</strong> <span id="phone">{{$logged_user->gender}}</span>
                  </li>
                  <li>
                    <i class="bi bi-chevron-right"></i>
                    <strong>Địa chỉ:</strong> <span id="day">{{$logged_user->address}}</span>
                  </li>
                  <li>
                    <i class="bi bi-chevron-right"></i>
                    <strong>Software:</strong> <span id="day">{{$logged_user->software}}</span>
                  </li>
                </ul>
              </div>
              <div class="col-lg-6">
                <ul>
                  <li>
                    <i class="bi bi-chevron-right"></i>
                    <strong>Công ty:</strong>
                    <span id="phone">{{$logged_user->company}}</span>
                  </li>
                  <li>
                    <i class="bi bi-chevron-right"></i>
                    <strong>Email:</strong>
                    <span id="address">{{$logged_user->email}}</span>
                  </li>
                  <li>
                    <i class="bi bi-chevron-right"></i> <strong>TAX:</strong>
                    <span id="email">{{$logged_user->tax_id}}</span>
                  </li>
                  <li>
                    <i class="bi bi-chevron-right"></i>
                    <strong>Website:</strong> <span id="name">{{$logged_user->website}}</span>
                  </li>
                  <li>
                    <i class="bi bi-chevron-right"></i>
                    <strong>Số điện thoại:</strong>
                    <span id="phone">{{$logged_user->phone}}</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- CHỈNH SỬA THÔNG TIN KHÁCH HÀNG -->
          <section id="about" class="about section">
            <div class="img-container">
              <button id="edit-btn" class="edit-button">
                Chỉnh sửa thông tin
              </button>
              <div class="btn btn-success me-3" id="openForm">Thay đổi mật khẩu</div>
            </div>
          </section>

          <div class="modal-overlay" id="modalOverlay"></div>
          <div class="modalPass reset-password-box" id="registrationForm">
              <span class="close_pass">x</span>
            <h1 class="reset-password-header">
              Đổi mật khẩu
            </h1>
            <form action="{{ route('account.changePass') }}" method="POST" enctype="multipart/form-data" class="reset-password-form">
              @csrf
              @method('PUT')

              <!-- Old Password -->
              <div class="old_pass_box input_box">
                <input type="password" name="old-password" id="old-password" class="input-field" required>
                <label for="old-password" class="label">Mật khẩu cũ</label>
                <i class="bx bx-show toggle-password icon" data-target="old-password"></i>
                @if ($errors->has('old-password'))
                <div class="error-message">{{ $errors->first('old-password') }}</div>
                @endif
              </div>

              <!-- New Password -->
              <div class="new_pass_box input_box">
                <input type="password" name="new-password" id="new-password" class="input-field" value="{{ old('new-password') }}" required>
                <label for="new-password" class="label">Mật khẩu mới</label>
                <i class="bx bx-show toggle-password icon" data-target="new-password"></i>
                <span class="error-message" id="password_error"></span>
                <div class="password-hint">
                  <strong class="strong1">Gợi ý để tạo mật khẩu an toàn:</strong>
                  <div class="hint-list">
                    <ul>
                      <li class="hint" id="hint_length">Tối thiểu 8 ký tự</li>
                      <li class="hint" id="hint_uppercase">1 chữ cái in hoa</li>
                      <li class="hint" id="hint_number">1 số</li>
                      <li class="hint" id="hint_special">1 ký tự đặc biệt</li>
                      <li class="hint" id="hint_lowercase">1 chữ thường</li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Confirm Password -->
              <div class="confirm_box input_box ">
                <input type="password" name="confirm-password" id="confirm-password" class="input-field" value="{{ old('confirm-password') }}" required>
                <label for="confirm-password" class="label">Xác nhận mật khẩu</label>
                <i class="bx bx-show toggle-password icon" data-target="confirm-password"></i>
                <span class="error-message" id="password_confirm_error"></span>
              </div>

              <!-- Submit Button -->
              <div class="input_box" style="align-items: center">
                <input type="submit" class="input-submit" value="Cập nhật mật khẩu">
              </div>
            </form>
          </div>


          <script>
            @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
              Swal.fire({
                title: 'Thành công!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
              });
            });
            @endif

            document.addEventListener('DOMContentLoaded', function() {
              // Hiển thị modal nếu có lỗi từ server
              if ("{{ $errors->any() }}") {
                document.getElementById('registrationForm').style.display = 'block';
                document.getElementById('modalOverlay').style.display = 'block';
              }

              const passwordInput = document.getElementById('new-password');
              const savedPassword = "{{ old('new-password') }}";
              const passwordConfirmInput = document.getElementById('confirm-password');
              const form = document.querySelector('.reset-password-form');
              if (savedPassword) {
                updateHints(savedPassword);
              }

              passwordInput.addEventListener('input', function() {
                const passwordValue = passwordInput.value;
                updateHints(passwordValue);
              });

              form.addEventListener('submit', function(e) {
                e.preventDefault();
                let hasError = false;
                // Password validation
                if (passwordInput.value.length < 8 || !/[A-Z]/.test(passwordInput.value) || !/[0-9]/.test(passwordInput.value) || !/[!@#$%^&*]/.test(passwordInput.value)) {
                  document.getElementById('password_error').textContent = 'Mật khẩu yếu: phải chứa ít nhất 8 ký tự bao gồm chữ in hoa, số và ký tự đặc biệt.';
                  hasError = true;
                }

                // Password confirmation validation
                if (passwordInput.value !== passwordConfirmInput.value) {
                  document.getElementById('password_confirm_error').textContent = 'Mật khẩu xác nhận không khớp.';
                  hasError = true;
                }

                // Nếu không có lỗi thì gửi form
                if (!hasError) {
                  form.submit();
                }
              })
            });
            const openFormButton = document.getElementById('openForm');
            const modalPass = document.getElementById('registrationForm');
            const overlay = document.getElementById('modalOverlay');
            const closeBtn_pass = document.querySelector(".close_pass");
            openFormButton.addEventListener('click', () => {
              modalPass.style.display = 'block';
              overlay.style.display = 'block';

            });

            overlay.addEventListener('click', () => {
              modalPass.style.display = 'none';
              overlay.style.display = 'none';
              const inputs = modalPass.querySelectorAll("input");
              inputs.forEach(input => {
                if (input.type === "submit") {
                  return;
                } else {
                  input.value = "";
                }
              });
            });

            document.querySelectorAll('.toggle-password').forEach(icon => {
              icon.addEventListener('click', () => {
                const targetId = icon.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);

                if (targetInput.type === 'password') {
                  targetInput.type = 'text';
                  icon.classList.remove('bx-show');
                  icon.classList.add('bx-hide');
                } else {
                  targetInput.type = 'password';
                  icon.classList.remove('bx-hide');
                  icon.classList.add('bx-show');
                }
              });
            });

            function updateHints(passwordValue) {
              const errorMessage = document.getElementById('password_error');

              // Reset màu sắc gợi ý
              document.querySelectorAll('.hint').forEach(hint => {
                hint.style.color = 'black';
              });

              // Thay đổi màu sắc dựa trên điều kiện
              if (passwordValue.length >= 8) {
                document.getElementById('hint_length').style.color = 'orange';
              }
              if (/[A-Z]/.test(passwordValue)) {
                document.getElementById('hint_uppercase').style.color = 'orange';
              }
              if (/[0-9]/.test(passwordValue)) {
                document.getElementById('hint_number').style.color = 'orange';
              }
              if (/[!@#$%^&*]/.test(passwordValue)) {
                document.getElementById('hint_special').style.color = 'orange';
              }
              if (/[a-z]/.test(passwordValue)) {
                document.getElementById('hint_lowercase').style.color = 'orange';
              }

              // Hiển thị thông báo lỗi nếu không đạt
              if (passwordValue.length < 8) {
                errorMessage.textContent = 'Mật khẩu phải có ít nhất 8 ký tự.';
              } else if (!/[A-Z]/.test(passwordValue)) {
                errorMessage.textContent = 'Mật khẩu phải có ít nhất một chữ cái viết hoa.';
              } else if (!/[0-9]/.test(passwordValue)) {
                errorMessage.textContent = 'Mật khẩu phải có ít nhất một số.';
              } else if (!/[!@#$%^&*]/.test(passwordValue)) {
                errorMessage.textContent = 'Mật khẩu phải có ít nhất một ký tự đặc biệt.';
              } else {
                errorMessage.textContent = '';
              }
            }
            closeBtn_pass.onclick = () => {
                modalPass.style.display = "none";
                overlay.style.display = "none";
            };

          </script>

          <!-- Modal -->
          <div id="editModal" class="modal">
            <div class="modal-content">
              <span class="close">x</span>
              <h1 class="modal-title">Chỉnh sửa thông tin khách hàng</h1>
              <form action="{{ route('customer.updateProfile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-container">
                  <!-- Cột 1 -->
                  <div class="form-column">
                    <div class="form-group">
                      <label for="full_name">Họ và Tên<span class="required">*</span></label>
                      <input type="text" name="full_name" id="full_name" value="{{$logged_user->full_name}}">
                    </div>
                    <div class="form-group">
                      <label for="date_of_birth">Năm sinh<span class="required">*</span></label>
                      <input type="date" name="date_of_birth" id="date_of_birth" value="{{$logged_user->date_of_birth->toDateString()}}">
                    </div>
                    <div class="form-group">
                      <label for="gender" class="form-label">Giới tính<span class="required">*</span></label>
                      <select id="gender" name="gender" class="form-control" required>
                        <option value="Nam" {{ $logged_user->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Nữ" {{ $logged_user->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="phone">Số điện thoại<span class="required">*</span></label>
                      <input type="text" name="phone" id="phone" value="{{$logged_user->phone}}">
                    </div>
                    <div class="form-group">
                      <label for="address">Địa chỉ</label>
                      <input type="text" name="address" id="address" value="{{$logged_user->address}}">
                    </div>
                    <div class="form-group">
                      <label for="software">Software</label>
                      <input type="text" name="software" id="software" value="{{$logged_user->software}}">
                    </div>
                  </div>

                  <!-- Cột 2 -->
                  <div class="form-column">
                    <div class="form-group">
                      <label for="company">Công Ty</label>
                      <input type="text" name="company" id="company" value="{{$logged_user->company}}">
                    </div>
                    <div class="form-group">
                      <label for="email">Email<span class="required">*</span></label>
                      <input type="text" name="email" id="email" value="{{$logged_user->email}}">
                    </div>
                    <div class="form-group">
                      <label for="tax-id">TAX</label>
                      <input type="text" name="tax_id" id="tax_id" value="{{$logged_user->tax_id}}">
                    </div>
                    <div class="form-group">
                      <label for="website">Website</label>
                      <input type="text" name="website" id="website" value="{{$logged_user->website}}">
                    </div>
                    <div class="form-group">
                      <div class="container-img">
                        <div class="form-group">
                          <label for="profile_image" class="form-label profile-image-label">Ảnh đại diện</label>
                          <div class="custom-file-upload">
                            <input type="file" id="profile_image" name="profile_image" class="form-control"
                              accept="image/*" onchange="previewImage(event)">
                            <label for="profile_image" class="custom-file-label">Chọn khác</label>
                            <div class="image-preview">

                              <div id="image-preview" class="image-preview">
                                <img id="preview-img"
                                  src="{{ $logged_user->profile_image ? asset('admin/img/customer/' . $logged_user->profile_image) : asset('admin/img/customer/default.png') }}"
                                  alt="Hình đại diện">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
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
              document.addEventListener("DOMContentLoaded", () => {
                  const modal = document.querySelector(".modal");
                  const btn = document.getElementById("edit-btn");
                  const closeBtn = document.querySelector(".close");
                  const cancelBtn = document.querySelector(".btn-cancel");

                  // Mở modal khi nhấn nút
                  btn.onclick = () => {
                      modal.style.display = "block";
                  };

                  // Đóng modal khi nhấn nút 'x'
                  closeBtn.onclick = () => {
                      modal.style.display = "none";
                  };

                  // Đóng modal khi nhấn nút "Hủy"
                  cancelBtn.onclick = () => {
                      modal.style.display = "none";
                  };

                  // Đóng modal khi click ra ngoài nội dung
                  window.onclick = (event) => {
                      if (event.target === modal) {
                          modal.style.display = "none";
                      }
                  };
              });

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
        <div class="container">
          <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">
            <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
              <li data-filter="*" class="filter-active">Tất cả</li>
              <li data-filter=".chua-xu-ly">Chưa xử lý</li>
              <li data-filter=".dang-xu-ly">Đang xử lý</li>
              <li data-filter=".hoan-thanh">Hoàn thành</li>
              <li data-filter=".da-huy">Đã hủy</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Lịch sử yêu cầu -->
    <section id="request-history" class="request-history section">
      <div class="container">
        @forelse ($requests as $request)
        <div class="request-item {{ Str::slug($request->status, '-') }}" onclick="viewRequestDetail('{{ $request->request_id }}')">
          <div class="request-info">
            <h3>{{ $request->request_id }}</h3>
            <span class="status {{ Str::slug($request->status, '-') }}">{{ $request->status }}</span>
            <p>{{ $request->subject }}</p>
          </div>
          <div class="request-arrow">→</div>
        </div>
        @empty
        <p>Không có yêu cầu nào trong lịch sử.</p>
        @endforelse
      </div>
    </section>

    <!-- Modal trạng thái -->
    <div id="modal-status" class="modal-request">
      <div class="modal-content-request">
        <span class="close-btn" onclick="closeForm()">×</span>
        <h2>Trạng thái yêu cầu</h2>
        <div id="status-timeline">
          <!-- Nội dung trạng thái sẽ được tạo động -->
        </div>
      </div>
    </div>

    <script>
      // Hàm gọi API để lấy trạng thái yêu cầu
      function fetchRequestStatus(requestId) {
        return fetch(`/api/request-status/${requestId}`, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
            }
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            console.log("Fetched request status:", data); // Debug log
            return data;
          })
          .catch(error => {
            console.error("Error fetching request status:", error);
            return [];
          });
      }

      // Hàm hiển thị trạng thái yêu cầu trong modal
      function renderRequestStatus(data) {
        const timeline = document.getElementById("status-timeline");
        timeline.innerHTML = ""; // Xóa nội dung trước đó

        console.log("Rendering request status:", data); // Debug log

        // Tạo các trạng thái động
        data.forEach((item, index) => {
          const isCompleted = index === data.length - 1 ? "completed" : "";

          const statusItem = `
                <div class="status-item ${isCompleted}">
                    <div class="circle"></div>
                    <div class="line"></div>
                    <span>${new Date(item.time).toLocaleString('vi-VN')}</span> - <span>${item.status}</span>
                </div>
            `;
          timeline.innerHTML += statusItem;
        });

        // Nếu không có dữ liệu, hiển thị thông báo
        if (data.length === 0) {
          timeline.innerHTML = "<p>Không có trạng thái nào để hiển thị.</p>";
        }
      }

      // Mở modal và hiển thị trạng thái yêu cầu
      function viewRequestDetail(requestId) {
        const modal = document.getElementById("modal-status");

        // Gọi API để lấy dữ liệu
        fetchRequestStatus(requestId).then(data => {
          renderRequestStatus(data);
          modal.style.display = "block";
        });
      }

      // Đóng modal
      function closeForm() {
        const modal = document.getElementById("modal-status");
        modal.style.display = "none";
      }

      // Đóng modal khi nhấn ngoài nội dung modal
      window.onclick = function(event) {
        const modal = document.getElementById("modal-status");
        if (event.target == modal) {
          modal.style.display = "none";
        }
      }

      // Script lọc yêu cầu
      document.addEventListener("DOMContentLoaded", function() {
        const filters = document.querySelectorAll(".portfolio-filters li");
        const requestItems = document.querySelectorAll(".request-item");

        filters.forEach((filter) => {
          filter.addEventListener("click", function() {
            const filterStatus = filter.getAttribute("data-filter");
            requestItems.forEach((item) => {
              if (filterStatus === "*" || item.classList.contains(filterStatus.substring(1))) {
                item.style.display = "flex";
              } else {
                item.style.display = "none";
              }
            });
            filters.forEach((f) => f.classList.remove("filter-active"));
            filter.classList.add("filter-active");
          });
        });
      });
    </script>

    <!-- Contact Section -->
    <section id="contact" class="contact section">
      <div class="container section-title" data-aos="fade-up">
        <h1>CÀI ĐẶT</h1>
      </div>

      <!-- Cards Section -->
      <div class="card-container">
        <!-- Card 2: CHUYỂN ĐỔI TÀI KHOẢN -->
        <div class="card" id="switch-account-card">
          <h2>CHUYỂN ĐỔI TÀI KHOẢN</h2>
        </div>
      </div>
    </section>

    <button id="openModal" class="btn-open-modal">Chuyển đổi tài khoản</button>

    <!-- Modal -->
    <div id="accountModal" class="modal-switch">
      <div class="modal-content-switch">
        <span class="close" id="btn-close-switch">&times;</span>
        <h2>Chuyển đổi tài khoản</h2>
        <ul class="account-list">
          <!-- Danh sách tài khoản -->
          @foreach ($accounts as $account)
          <li class="account-item">
            <form action="{{ route('account.switch',$account->id) }}" method="POST">
              @csrf
              <img src="{{ $account->customer->profile_image ? asset('admin/img/customer/' . $account->customer->profile_image) : asset('admin/img/customer/default.png') }}" alt="" class="avatar">
              <span class="account-name">{{ $account->customer->full_name }}</span>
              <button type="submit" class="btn-switch">Chuyển</button>
            </form>
          </li>
          @endforeach
        </ul>
        <a class="login-button" href="{{ route('login') }}">Thêm tài khoản mới</a>
      </div>
    </div>
    <script>
      // Lấy các phần tử
      const modal_switch = document.getElementById('accountModal');
      const openModalBtn = document.getElementById('openModal');
      const closeModal = document.getElementById('btn-close-switch');

      // Mở modal khi nhấn nút
      openModalBtn.addEventListener('click', () => {
        modal_switch.style.display = 'block';
      });

      // Đóng modal khi nhấn vào nút đóng
      closeModal.addEventListener('click', () => {
        modal_switch.style.display = 'none';
      });

      // Đóng modal khi nhấn vào bất kỳ chỗ nào bên ngoài modal
      window.addEventListener('click', (event) => {
        if (event.target === modal_switch) {
          modal_switch.style.display = 'none';
        }
      });
    </script>
    </script>
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center active"><i class="bi bi-arrow-up-short"></i></a>
    <!-- Main JS File -->
    <script src="guest/js/main.js"></script>

</body>

</html>