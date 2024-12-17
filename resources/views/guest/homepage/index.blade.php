<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ Sweetsoft</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="guest/css/homepage/homepage.css?v=1">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet" />
    <style>
        .carousel-card {
            transition: transform 0.3s ease; /* Hiệu ứng chuyển động */
            cursor: pointer; /* Con trỏ chuột */
            position: relative; /* Định vị cho các phần tử con */
        }

        .carousel-card.expanded {
            transform: scale(1.4); /* Phóng to lên 40% */
            z-index: 10; /* Đưa card lên trên cùng */
        }

        .article-details {
            position: absolute; /* Định vị tuyệt đối để hiển thị bên trong card */
            bottom: 10px; /* Cách đáy */
            left: 10px; /* Cách bên trái */
            color: #333; /* Màu chữ */
            background: rgba(255, 255, 255, 0.8); /* Nền mờ */
            padding: 10px;
            border-radius: 5px;
            transition: opacity 0.3s ease; /* Hiệu ứng mờ */
        }
        .close {
            position: absolute; /* Định vị tuyệt đối */
            top: 10px; /* Khoảng cách từ trên xuống */
            right: 15px; /* Khoảng cách từ bên phải */
            font-size: 28px;
            font-weight: bold;
            color: #aaa; /* Màu sắc */
        }
        .close:hover,
        .close:focus {
            color: black; /* Màu khi hover */
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>


    <header>
        <div class="logo">
            <img src="guest/img/swsoft_logo.svg" alt="Logo">
        </div>
        <nav class="nav-links">
            <a href="#home">Trang chủ</a>
            <a href="#faq">Bài viết</a>
            <a href="#ins">Hướng dẫn</a>
            <a href="#contact">Liên hệ</a>
            <a class="login-button" href="{{ route('login') }}">Đăng nhập</a>
        </nav>
    </header>
    <div class="main-content" id="home">
        <div class="left">
            <img src="guest/img/trangchu.png" alt="Sample Image">

        </div>
        <div class="right">
            <h1>
                <span>TRANG HỖ TRỢ</span>
                <span>KHÁCH HÀNG</span>
            </h1>
            <div class="search-container">
                <input type="text" placeholder="Tìm kiếm...">
                <button><img src='guest/img/search.png' alt='Search' style='width: 20px; height: 20px;'></button>
            </div>
        </div>
    </div>



    <section class="faq-section" id="faq">
        <h1 class="faq-title">Bài viết</h1> <!-- Thêm tiêu đề riêng -->
        <div class="faq-container">

            <ul class="faq-list">
                @forelse ($faqs as $faq)
                    <li>
                        <a href="#" class="faq-question" data-id="{{ $faq->faq_id }}">{{ $faq->question }}</a>
                    </li>
                @empty
                    <li>Không có câu hỏi nào được phản hồi.</li>
                @endforelse
            </ul>
            <div id="faq-answer-container" style="display: none;">
                <h3>Trả lời:</h3><p id="faq-answer"></p>
            </div>
            <div id="faqModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span id="closeModal" style="float: right; cursor: pointer;">&times;</span>
                    <h3 id="modal-question"></h3>
                    <p id="modal-answer"></p>
                </div>
            </div>

            <div class="faq-form-container">
                <button id="ask-question-button">Đặt câu hỏi</button>
                <div id="question-form">
                    <input id="question-name" type="text" placeholder="Nhập email của bạn" />
                    <textarea id="question-text" placeholder="Nhập câu hỏi của bạn" rows="5"></textarea>
                    <button id="submit-question-button">Gửi</button>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const faqQuestions = document.querySelectorAll(".faq-question");
    const modal = document.getElementById("faqModal");
    const modalQuestion = document.getElementById("modal-question");
    const modalAnswer = document.getElementById("modal-answer");
    const closeModal = document.getElementById("closeModal");

    faqQuestions.forEach(question => {
        question.addEventListener("click", function (event) {
            event.preventDefault();

            const faqId = this.getAttribute("data-id");

            fetch(`/faq/answer/${faqId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalQuestion.textContent = this.textContent; // Hiển thị câu hỏi
                        modalAnswer.textContent = data.answer || "Chưa có câu trả lời."; // Hiển thị câu trả lời
                        modal.style.display = "block"; // Hiển thị modal
                    } else {
                        alert("Không tìm thấy câu trả lời.");
                    }
                })
                .catch(error => {
                    console.error("Lỗi khi gọi API:", error);
                    alert("Có lỗi xảy ra, vui lòng thử lại.");
                });
        });
    });

    // Đóng modal khi click vào nút đóng
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Đóng modal khi click ra ngoài
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});

    </script>








    <!-- Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const askButton = document.getElementById("ask-question-button");
            const questionForm = document.getElementById("question-form");

            askButton.addEventListener("click", function() {
                // Toggle visibility of the form
                if (questionForm.style.display === "none" || questionForm.style.display === "") {
                    questionForm.style.display = "block"; // Show the form
                } else {
                    questionForm.style.display = "none"; // Hide the form
                }
            });
        });
    </script>


    <hr style="border: none; border-top: 1px solid #ccc; margin: 20px auto; width: 50%;">
    <div class="carousel-container" id="ins">
        <div class="instructions">Hướng dẫn</div>
        <div class="carousel" id="carousel">
            @foreach($articles as $article)
                <div class="carousel-card" onclick="toggleCard(this, '{{ $article->title }}', '{{ $article->content }}', '{{ $article->create_at ? \Carbon\Carbon::parse($article->create_at)->format('d/m/Y') : 'Chưa có ngày đăng' }}')">
                    <h3 class="article-title">{{ $article->title }}</h3>
                    <div class="article-details" style="display: none;">
                        <p class="article-content"></p>
                        <p class="article-date"></p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="carousel-controls">
            <button class="carousel-button left" id="prev">&#8249;</button>
            <button class="carousel-button right" id="next">&#8250;</button>
        </div>
    </div>

    <!-- Modal để hiển thị thông tin chi tiết -->
    <!-- Modal -->
    <div id="articleModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()" style="cursor: pointer;">&times;</span>
            <h3 id="modalTitle"></h3>
            <p id="modalContent"></p>
            <p id="modalDate"></p>
        </div>
    </div>

    <script>
        function toggleCard(cardElement, title, content, date) {
            const isExpanded = cardElement.classList.contains('expanded');

            // Đóng tất cả các card khác
            const cards = document.querySelectorAll('.carousel-card');
            cards.forEach(card => {
                card.classList.remove('expanded');
                card.querySelector('.article-details').style.display = 'none'; // Ẩn thông tin chi tiết
            });

            // Nếu card chưa phóng to, phóng to card đã nhấn
            if (!isExpanded) {
                cardElement.classList.add('expanded');
                const details = cardElement.querySelector('.article-details');
                details.querySelector('.article-content').innerText = content;
                details.querySelector('.article-date').innerText = date;
                details.style.display = 'block'; // Hiển thị thông tin chi tiết
            }
        }
        function showArticleDetails(title, content, date) {
            console.log(title, content, date); // Kiểm tra dữ liệu đầu vào
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalContent').innerText = content;
            document.getElementById('modalDate').innerText = date;

            const modal = document.getElementById('articleModal');
            modal.style.display = "block";
            modal.classList.add('show'); // Thêm lớp 'show' để hiển thị hiệu ứng
        }

        function closeModal() {
            const modal = document.getElementById('articleModal');
            modal.classList.remove('show'); // Loại bỏ lớp 'show' để áp dụng hiệu ứng thu nhỏ
            setTimeout(() => {
                modal.style.display = "none"; // Ẩn modal sau khi hiệu ứng hoàn tất
            }, 300); // Thời gian trùng với thời gian hiệu ứng
        }
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const carousel = document.getElementById("carousel");
            const prevButton = document.getElementById("prev");
            const nextButton = document.getElementById("next");

            let currentIndex = 0;
            const totalCards = carousel.children.length;

            const updateCarousel = () => {
                const offset = currentIndex * -330; // Adjusted for larger card width and margin
                carousel.style.transform = `translateX(${offset}px)`;
            };

            prevButton.addEventListener("click", function() {
                currentIndex = (currentIndex === 0) ? totalCards - 2 : currentIndex - 1;
                updateCarousel();
            });

            nextButton.addEventListener("click", function() {
                currentIndex = (currentIndex === totalCards - 2) ? 0 : currentIndex + 1;
                updateCarousel();
            });

            // Ensure only 2 cards are visible at a time
            const containerWidth = 660; // Adjusted for larger cards (300px each + margin)
            document.querySelector('.carousel-container').style.width = `${containerWidth}px`;
        });
    </script>

    <hr style="border: none; border-top: 1px solid #ccc; margin: 20px auto; width: 50%;">
    <footer class="footer" id="contact" style="background-color: #f9f9f9; padding: 20px 0;">
        <div style="display: flex; justify-content: space-around;">
            <!-- Column 1 -->
            <div style="flex: 1; padding: 0 10px;">
                <img src="guest/img/logosweetsoft.png" alt="SweetSoft Logo" style="width: 300px; margin-bottom: 10px;">
                <p>Thiết kế web Nha Trang, công ty phần mềm SweetSoft</p>
                <p>Công ty phần mềm chuyên nghiệp hàng đầu tại Nha Trang. Website đẳng cấp mang lại giá trị cao cho khách hàng.</p>
                <p>Theo dõi chúng tôi
                    <i class="fab fa-facebook" style="font-size: 24px; color: #6F2F9F; margin-left: 10px;"></i>
                    <i class="fab fa-youtube" style="font-size: 24px; color: #6F2F9F; margin-left: 10px;"></i>
                    <i class="fas fa-map-marker-alt" style="font-size: 24px; color: #6F2F9F; margin-left: 10px;"></i>
                </p>
            </div>

            <!-- Column 2 -->
            <div style="flex: 1; padding: 0 10px;">
                <h2>Liên hệ</h2>
                <p>Văn phòng: Ô 10 Tầng 12A - Tòa nhà VCN Tower, 02 Tố Hữu, khu đô thị VCN, P.Phước Hải, Tp Nha Trang, tỉnh Khánh Hòa, Việt Nam</p>
                <p>Email: info@sweetsoft.vn</p>
                <p>Điện thoại: 0258.3704199 - 0258.6567900</p>
                <p>Thứ hai - Thứ sáu 7:30–17:00 Thứ bảy 7:30–11:30 <br> Cuối tuần nghỉ</p>
            </div>

            <!-- Column 3 -->
            <div style="flex: 1; padding: 0 10px;">
                <h2>Dịch vụ của chúng tôi</h2>
                <ul style="list-style-type: '-';padding-left: 20px;">
                    <li style="margin-bottom: 12px; padding-left: 10px;">Thiết kế web</li>
                    <li style="margin-bottom: 12px; padding-left: 10px;">Thuê hosting</li>
                    <li style="margin-bottom: 12px; padding-left: 10px;">Đăng ký tên miền</li>
                    <li style="margin-bottom: 12px; padding-left: 10px;">Phát triển phần mềm</li>
                    <li style="margin-bottom: 12px; padding-left: 10px;">Dịch vụ trực tuyến</li>
                </ul>
            </div>

            <!-- Column 4 -->
            <div style="flex: 1; text-align: center; padding: 0 10px; position: relative; max-width: 350px; margin: 0 auto;">
                <h2>Sweetsoft trên Facebook</h2>

                <img src="guest/img/footer_fb.png" alt="SweetSoft Promo" style="width: 100%; margin-top: 10px; position: relative; z-index: 0;">
            </div>
        </div>

        <!-- Bottom Bar -->

    </footer>
    <div style="background-color: #6a1b9a; color: #fff; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center;">
        <span>&copy; 2024 Công Ty Cổ Phần SweetSoft.</span>
        <span>
            <a href="#" style="color: #fff; text-decoration: none; margin-right: 10px;">Điều khoản & điều kiện</a> |
            <a href="#" style="color: #fff; text-decoration: none; margin-right: 10px;">Chính sách bảo mật</a> |
            <a href="#" style="color: #fff; text-decoration: none;">Sơ đồ</a>
        </span>
    </div>

    <!-- Nút nổi -->
    <div class="floating-button" id="openForm">
    </div>

    <!-- Overlay -->
    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal" id="registrationForm">
        <h2>FORM YÊU CẦU</h2>
        <p style="color: red;">Hãy đăng nhập để được gửi yêu cầu hỗ trợ</p>
        <form id="registerForm">
            <div>
                <button type="button" onclick="window.location.href='{{ route('login') }}'" style="padding: 10px 20px; background-color: #6F2F9F; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    Đăng nhập
                </button>
                <!-- Kiểm tra trạng thái đăng nhập, nếu đã đăng nhập thì hiển thị nút yêu cầu hỗ trợ -->
                <button type="button" id="request-button" style="padding: 10px 20px; background-color: #6F2F9F; color: white; border: none; border-radius: 5px; cursor: pointer; display: none;">
                    Gửi yêu cầu
                </button>
            </div>
            <a href="#" style="display: block; margin-top: 10px;">Hướng dẫn thao tác gửi yêu cầu hỗ trợ</a>
        </form>

    </div>


    <script>
        // Lấy các phần tử HTML
        const openFormButton = document.getElementById('openForm');
        const modal = document.getElementById('registrationForm');
        const overlay = document.getElementById('modalOverlay');

        // Mở form khi ấn nút
        openFormButton.addEventListener('click', () => {
            modal.style.display = 'block';
            overlay.style.display = 'block';
        });

        // Đóng form khi ấn ra ngoài
        overlay.addEventListener('click', () => {
            modal.style.display = 'none';
            overlay.style.display = 'none';
        });

        // Xử lý khi gửi form
        const registerForm = document.getElementById('registerForm');
        registerForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Ngăn form tự động reload trang
            alert('Thông tin đã được gửi!');
            modal.style.display = 'none';
            overlay.style.display = 'none';
            registerForm.reset(); // Reset form sau khi gửi
        });

        document.addEventListener('DOMContentLoaded', function() {
            const openFormButton = document.getElementById('openForm');
            const modal = document.getElementById('registrationForm');
            const overlay = document.getElementById('modalOverlay');
            const loginButton = document.getElementById('login-button');
            const requestButton = document.getElementById('request-button');
            const loginPrompt = document.getElementById('login-prompt');

            // Giả sử bạn có thể kiểm tra trạng thái đăng nhập từ session, ví dụ từ Laravel session
            const isLoggedIn = {!! Auth::check() ? 'true' : 'false' !!}; // Laravel Check (nếu bạn sử dụng Laravel)

            // Mở form khi ấn nút
            openFormButton.addEventListener('click', () => {
                modal.style.display = 'block';
                overlay.style.display = 'block';

                if (isLoggedIn) {
                    loginPrompt.style.display = 'none';
                    requestButton.style.display = 'block';
                    loginButton.style.display = 'none';
                } else {
                    loginPrompt.style.display = 'block';
                    requestButton.style.display = 'none';
                    loginButton.style.display = 'block';
                }
            });

            // Đóng form khi ấn ra ngoài
            overlay.addEventListener('click', () => {
                modal.style.display = 'none';
                overlay.style.display = 'none';
            });

            // Khi người dùng nhấn "Gửi yêu cầu", chuyển hướng đến trang yêu cầu
            requestButton.addEventListener('click', function() {
                window.location.href = '{{ route('showFormRequest') }}'; // Điều hướng đến trang yêu cầu
            });
        });

    </script>



</body>

</html>
