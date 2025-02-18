<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

</head>

<body>


    <header>
        <div class="logo">
            <img src="guest/img/swsoft_logo.svg" alt="Logo">
        </div>
        <nav class="nav-links">
            <a href="#home">Trang chủ</a>
            <a href="#faq">Câu hỏi</a>
            <a href="#ins">Hướng dẫn</a>
            <a href="#contact">Liên hệ</a>
            @if (auth()->check())
            <img class="avatar-container" src="{{$logged_user->profile_image ? asset('admin/img/customer/' .  $logged_user->profile_image) : asset('admin/img/customer/default.png') }}" alt="" onclick="toggleMenu()">

            <div class="menu" id="menu">
                <div class="menu-section-1">
                    <div class="menu-item">
                        <a href=" {{route("indexAccount")}}">Trang cá nhân</a>
                    </div>
                </div>
                <div class="menu-section-2">
                    <a href="{{ route('logout') }}" class="logout-button ">Đăng Xuất</a>
                </div>
            </div>

            <script>
                function toggleMenu(event) {
                    var menu = document.getElementById("menu");
                    var avatar = document.querySelector(".avatar-container");
                    menu.style.display = menu.style.display === "block" ? "none" : "block";

                    event.stopPropagation();
                }

                document.addEventListener("click", function(event) {
                    var menu = document.getElementById("menu");
                    var avatar = document.querySelector(".avatar-container");

                    if (!avatar.contains(event.target) && !menu.contains(event.target)) {
                        menu.style.display = "none";
                    }
                });
            </script>
            @else
            <a class="login-button" href="{{ route('login') }}">Đăng nhập</a>
            @endif
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
                <input id="search-keyword" type="text" placeholder="Nhập từ khóa tìm kiếm...">
                <select id="search-type" style="width: 150px;">
                    <option value="faq">Câu hỏi</option>
                    <option value="article">Hướng dẫn</option>
                </select>
                <div id="search-results" class="dropdown-menu d-none" style="position: absolute;"></div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const keywordInput = document.getElementById('search-keyword');
            const searchType = document.getElementById('search-type');
            const searchResults = document.getElementById('search-results');
            const assetBasePath = "{{ asset('admin/img/articles/') }}";

            function fetchSearchResults() {
                const keyword = keywordInput.value.trim();
                const type = searchType.value;

                if (keyword.length > 0) {
                    fetch(`/search?keyword=${encodeURIComponent(keyword)}&type=${type}`)
                        .then(response => response.json())
                        .then(data => {

                            searchResults.innerHTML = '';

                            if (data.length > 0) {
                                data.forEach(item => {
                                    const resultItem = document.createElement('a');
                                    resultItem.classList.add('dropdown-item');
                                    console.log(item)

                                    if (type === 'faq') {
                                        resultItem.setAttribute('data-id', item.faq_id);
                                    } else if (type === 'article') {
                                        resultItem.setAttribute(
                                            'onclick',
                                            `openHuongdanModal(this, '${item.title}', '${item.content}', '${item.create_at ? new Date(item.create_at).toLocaleDateString('en-GB') : 'Chưa có ngày đăng'}', '${assetBasePath}/${item.images}')`
                                        );
                                    }

                                    resultItem.innerHTML = `

                                <strong>${item.title || item.question}</strong>
                            `;
                                    searchResults.appendChild(resultItem);
                                });

                                // Hiển thị dropdown kết quả
                                searchResults.classList.remove('d-none');
                                searchResults.classList.add('d-block');

                                if (type === 'faq') {
                                    const faqQuestions = document.querySelectorAll(".dropdown-item");

                                    faqQuestions.forEach(question => {
                                        question.addEventListener("click", function(event) {
                                            event.preventDefault();

                                            const faqId = question.getAttribute("data-id");

                                            fetch(`/faq/answer/${faqId}`)
                                                .then(response => response.json())
                                                .then(data => {
                                                    const faqModal = document.getElementById("faqModal");
                                                    const modalQuestion = document.getElementById("modal-question");
                                                    const modalAnswer = document.getElementById("modal-answer");
                                                    modalQuestion.textContent = data.question;
                                                    modalAnswer.textContent = data.answer;

                                                    faqModal.style.display = "block";
                                                })
                                                .catch(error => {
                                                    console.error("Lỗi khi tải câu trả lời:", error);
                                                });
                                        });
                                    });
                                }

                            } else {
                                // Ẩn dropdown nếu không có kết quả
                                searchResults.classList.add('d-none');
                                searchResults.classList.remove('d-block');
                            }
                        });
                } else {
                    // Nếu không có từ khóa, ẩn dropdown
                    searchResults.innerHTML = '';
                    searchResults.classList.add('d-none');
                    searchResults.classList.remove('d-block');
                }
            }

            function hideDropdown() {
                searchResults.innerHTML = '';
                searchResults.classList.add('d-none');
                searchResults.classList.remove('d-block');
            }

            // Lắng nghe sự kiện input trên ô tìm kiếm
            keywordInput.addEventListener('input', fetchSearchResults);

            // Lắng nghe sự kiện thay đổi lựa chọn trong select
            searchType.addEventListener('change', fetchSearchResults);

            keywordInput.addEventListener('blur', function(event) {
                setTimeout(() => {
                    hideDropdown();
                }, 200);
            });

            // Hiển thị dropdown khi lấy lại tiêu điểm
            keywordInput.addEventListener('focus', fetchSearchResults);
        });
    </script>
    <hr style="border: none; border-top: 1px solid #ccc; margin: 20px auto; width: 50%;">
    <section class="faq-section" id="faq">
        <h1 class="faq-title">Câu hỏi thường gặp</h1> <!-- Thêm tiêu đề riêng -->
        <div class="faq-container">
            <ul class="faq-list">
                @forelse ($faqs as $faq)
                <li class="faq-item">
                    <a href="#" class="faq-question" data-id="{{ $faq->faq_id }}">{{ $faq->question }}</a>
                </li>
                @empty
                <li>Không có câu hỏi nào được phản hồi.</li>
                @endforelse
            </ul>




            <div id="faq-answer-container" style="display: none;">
                <h3>Trả lời:</h3>
                <p id="faq-answer"></p>
            </div>
            <div id="faqModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span id="closeModal" class="closefaq" style="float: right; cursor: pointer;">&times;</span>
                    <h3 id="modal-question" style="font-weight: bold;"></h3>
                    <p id="modal-answer" style="margin-top: 10px;"></p>
                </div>
            </div>
            {{-- Xử lý hiển thị modal câu hỏi --}}
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const faqQuestions = document.querySelectorAll(".faq-question");
                    const faqModal = document.getElementById("faqModal");
                    const modalQuestion = document.getElementById("modal-question");
                    const modalAnswer = document.getElementById("modal-answer");
                    const closeModal = document.getElementById("closeModal");

                    // Xử lý khi nhấn vào câu hỏi
                    faqQuestions.forEach(question => {
                        question.addEventListener("click", function(event) {
                            event.preventDefault(); // Ngăn chặn hành động mặc định của liên kết

                            const faqId = question.getAttribute("data-id");

                            // Gọi API hoặc lấy dữ liệu trả lời dựa trên `faqId`
                            fetch(`/faq/answer/${faqId}`)
                                .then(response => response.json())
                                .then(data => {
                                    // Hiển thị câu hỏi và câu trả lời trong modal
                                    modalQuestion.textContent = data.question;
                                    modalAnswer.textContent = data.answer;

                                    // Hiển thị modal
                                    faqModal.style.display = "block";
                                })
                                .catch(error => {
                                    console.error("Lỗi khi tải câu trả lời:", error);
                                });
                        });
                    });

                    // Đóng modal khi nhấn vào nút đóng
                    closeModal.addEventListener("click", function() {
                        faqModal.style.display = "none";
                    });

                    // Đóng modal khi nhấn ra ngoài
                    window.addEventListener("click", function(event) {
                        if (event.target === faqModal) {
                            faqModal.style.display = "none";
                        }
                    });
                });
            </script>

            <div class="faq-form-container">
                <button id="ask-question-button">Đặt câu hỏi</button>
                <div id="question-form">
                    <input id="question-name" type="email" placeholder="Nhập email của bạn" required />
                    <span id="email-error" style="color: red; font-size: 14px; display: none;"></span>

                    <textarea id="question-text" placeholder="Nhập câu hỏi của bạn" rows="5" required></textarea>
                    <span id="question-error" style="color: red; font-size: 14px; display: none;"></span>

                    <button id="submit-question-button">Gửi</button>
                    <span id="form-success" style="color: green; font-size: 14px; display: none;"></span>
                </div>
            </div>

        </div>

    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const submitButton = document.getElementById("submit-question-button");
            const questionName = document.getElementById("question-name");
            const questionText = document.getElementById("question-text");
            const emailError = document.getElementById("email-error");
            const questionError = document.getElementById("question-error");
            const formSuccess = document.getElementById("form-success");

            // Hàm kiểm tra email hợp lệ
            function validateEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            submitButton.addEventListener("click", function(event) {
                event.preventDefault(); // Ngăn form reload
                const email = questionName.value.trim();
                const question = questionText.value.trim();

                // Xóa thông báo lỗi và thông báo thành công trước đó
                emailError.style.display = "none";
                questionError.style.display = "none";
                formSuccess.style.display = "none";

                let isValid = true;

                // Kiểm tra email
                if (!email) {
                    emailError.style.display = "block";
                    emailError.textContent = "Vui lòng nhập email.";
                    isValid = false;
                } else if (!validateEmail(email)) {
                    emailError.style.display = "block";
                    emailError.textContent = "Email không đúng định dạng.";
                    isValid = false;
                }

                // Kiểm tra câu hỏi
                if (!question) {
                    questionError.style.display = "block";
                    questionError.textContent = "Vui lòng nhập câu hỏi.";
                    isValid = false;
                }

                if (!isValid) {
                    return; // Dừng nếu có lỗi
                }

                // Gửi dữ liệu qua AJAX
                fetch('/faq/storeAjax', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: email,
                            question: question
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw errorData; // Ném lỗi ra ngoài để xử lý trong .catch()
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        formSuccess.style.display = "block";
                        formSuccess.style.color = "green";
                        formSuccess.textContent = data.message;

                        questionName.value = '';
                        questionText.value = '';
                    })
                    .catch(error => {
                        emailError.style.display = "none";
                        questionError.style.display = "none";

                        if (error.errors) {
                            if (error.errors.email) {
                                emailError.style.display = "block";
                                emailError.textContent = error.errors.email[0];
                            }
                            if (error.errors.question) {
                                questionError.style.display = "block";
                                questionError.textContent = error.errors.question[0];
                            }
                        }
                    });


            });
        });


        // Đóng modal khi click vào nút đóng
        closeModal.addEventListener("click", function() {
            modal.style.display = "none";
        });

        // Đóng modal khi click ra ngoài
        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    </script>

    <!-- Script -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const submitButton = document.getElementById("submit-question-button");
            const questionName = document.getElementById("question-name");
            const questionText = document.getElementById("question-text");
            const emailError = document.getElementById("email-error");
            const questionError = document.getElementById("question-error");
            const formSuccess = document.getElementById("form-success");

            // Hàm kiểm tra email hợp lệ
            function validateEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            submitButton.addEventListener("click", function(event) {
                event.preventDefault(); // Ngăn form reload
                const email = questionName.value.trim();
                const question = questionText.value.trim();

                // Xóa thông báo lỗi và thông báo thành công trước đó
                emailError.style.display = "none";
                questionError.style.display = "none";
                formSuccess.style.display = "none";

                let isValid = true;

                // Kiểm tra email
                if (!email) {
                    emailError.style.display = "block";
                    emailError.textContent = "Vui lòng nhập email.";
                    isValid = false;
                } else if (!validateEmail(email)) {
                    emailError.style.display = "block";
                    emailError.textContent = "Email không đúng định dạng.";
                    isValid = false;
                }

                // Kiểm tra câu hỏi
                if (!question) {
                    questionError.style.display = "block";
                    questionError.textContent = "Vui lòng nhập câu hỏi.";
                    isValid = false;
                }

                if (!isValid) {
                    return; // Dừng nếu có lỗi
                }

                // Gửi dữ liệu qua AJAX
                fetch('/faq/storeAjax', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: email,
                            question: question
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw errorData; // Ném lỗi ra ngoài để xử lý trong .catch()
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Hiển thị thông báo thành công
                        formSuccess.style.display = "block";
                        formSuccess.style.color = "green";
                        formSuccess.textContent = data.message;

                        // Xóa dữ liệu trong form
                        questionName.value = '';
                        questionText.value = '';
                    })
                    .catch(error => {
                        // Hiển thị lỗi chi tiết từ server
                        if (error.errors) {
                            if (error.errors.email) {
                                emailError.style.display = "block";
                                emailError.textContent = error.errors.email[0];
                            }
                            if (error.errors.question) {
                                questionError.style.display = "block";
                                questionError.textContent = error.errors.question[0];
                            }
                        }
                    });
            });
        });
    </script>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const submitButton = document.getElementById("submit-question-button");
            const questionName = document.getElementById("question-name");
            const questionText = document.getElementById("question-text");

            submitButton.addEventListener("click", function(event) {
                event.preventDefault(); // Ngăn chặn form reload trang
                const email = questionName.value.trim();
                const question = questionText.value.trim();

                if (!email || !question) {
                    return; // Dừng lại nếu các trường bị trống
                }

                fetch('/faq/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            email: email,
                            question: question
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Câu hỏi của bạn đã được gửi thành công!'
                            });

                            // Xóa dữ liệu trong form nếu thành công
                            questionName.value = '';
                            questionText.value = '';
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Đã xảy ra lỗi khi gửi câu hỏi. Vui lòng thử lại.'
                            });
                        }
                        // Không làm gì khác nếu thành công hoặc thất bại
                    })
                    .catch(error => {
                        console.error("Lỗi khi gửi dữ liệu:", error);
                    });
            });
        });
    </script>




    <button id="toggle-button" class="toggle-button">Xem thêm</button>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const faqList = document.querySelector(".faq-list");
            const faqItems = document.querySelectorAll(".faq-item");
            const toggleButton = document.getElementById("toggle-button");

            const MAX_VISIBLE_ITEMS = 8;

            // Hiển thị hoặc ẩn nút "Xem thêm"
            if (faqItems.length > MAX_VISIBLE_ITEMS) {
                toggleButton.style.display = "block";
                faqItems.forEach((item, index) => {
                    if (index >= MAX_VISIBLE_ITEMS) {
                        item.style.display = "none"; // Ẩn các mục vượt quá giới hạn
                    }
                });
            }

            // Xử lý sự kiện khi nhấn nút "Xem thêm"
            toggleButton.addEventListener("click", function() {
                const isExpanded = toggleButton.getAttribute("data-expanded") === "true";

                if (isExpanded) {
                    // Thu gọn danh sách
                    faqItems.forEach((item, index) => {
                        if (index >= MAX_VISIBLE_ITEMS) {
                            item.style.display = "none";
                        }
                    });
                    toggleButton.textContent = "Xem thêm";
                    toggleButton.setAttribute("data-expanded", "false");
                } else {
                    // Mở rộng danh sách
                    faqItems.forEach(item => {
                        item.style.display = "list-item";
                    });
                    toggleButton.textContent = "Thu gọn";
                    toggleButton.setAttribute("data-expanded", "true");
                }
            });
        });
    </script>
    <hr style="border: none; border-top: 1px solid #ccc; margin: 20px auto; width: 50%;">
    <div class="carousel-container" id="ins">
        <div class="instructions">Bài viết hướng dẫn</div>
        <div class="carousel" id="carousel">
            @foreach($articles as $article)
            <div class="carousel-card" data-id="{{ $article->article_id }}" onclick="openHuongdanModal(this, '{{ $article->title }}', '{{ $article->content }}', 'Ngày đăng: {{ $article->create_at ? \Carbon\Carbon::parse($article->create_at)->format('d/m/Y') : 'Chưa có ngày đăng' }}')">
                <img src="{{ asset('admin/img/articles/' . $article->images) }}" alt="Hình ảnh {{ $article->title }}">

                <h3 class="article-title">{{ $article->title }}</h3>
                <div class="article-details" style="display: none;">
                    <p class="article-content">{{$article->content }}</p>
                    <p class="article-date">Ngày đăng: {{ \Carbon\Carbon::parse($article->create_at)->format('d/m/Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="carousel-controls">
            <button class="carousel-button left" id="prev">&#8249;</button>
            <button class="carousel-button right" id="next">&#8250;</button>
        </div>
    </div>

    <!-- Modal Overlay -->
    <div id="huongdanModalOverlay" class="huongdan-modal-overlay" onclick="closeHuongdanModal()"></div>
    <div id="huongdanArticleModal" class="huongdan-modal">
        <span class="close" onclick="closeHuongdanModal()" style="cursor: pointer;">&times;</span>
        <img id="huongdanModalImage" src="" alt="Article Image">
        <div id="huongdanModalDateBox">
            <p id="huongdanModalDate"></p>
        </div>

        <h3 id="huongdanModalTitle"></h3>
        <p id="huongdanModalContent"></p>
        <button id="copyLinkButton" onclick="copyModalLink()">Sao chép liên kết</button>
    </div>

    <script>
        function openHuongdanModal(cardElement, title, content, date, img) {
            document.getElementById('huongdanModalTitle').innerText = title;
            document.getElementById('huongdanModalContent').innerHTML = content;
            document.getElementById('huongdanModalDate').innerText = date;

            if (img == null) {
                const imageSrc = cardElement.querySelector('img').getAttribute('src');
                document.getElementById('huongdanModalImage').setAttribute('src', imageSrc);
            } else {
                document.getElementById('huongdanModalImage').setAttribute('src', img);
            }

            const modal = document.getElementById('huongdanArticleModal');
            modal.style.display = "block";

            const overlay = document.getElementById('huongdanModalOverlay');
            overlay.style.display = "block";

            // Cập nhật URL với article ID
            const articleId = cardElement.getAttribute('data-id');
            const newUrl = `${window.location.origin}${window.location.pathname}?article=${articleId}`;
            console.log(newUrl);
            window.history.pushState({
                path: newUrl
            }, '', newUrl);
        }


        function closeHuongdanModal() {
            const modal = document.getElementById('huongdanArticleModal');
            modal.style.display = "none";

            const overlay = document.getElementById('huongdanModalOverlay');
            overlay.style.display = "none";

            // Xóa tham số article khỏi URL
            const newUrl = window.location.pathname;
            window.history.pushState({
                path: newUrl
            }, '', newUrl);
        }

        function copyModalLink() {
            const params = new URLSearchParams(window.location.search);
            const articleId = params.get('article'); // Lấy ID bài viết từ URL hiện tại
            const link = articleId ?
                `${window.location.origin}${window.location.pathname}?article=${articleId}` :
                `${window.location.origin}${window.location.pathname}`;

            navigator.clipboard.writeText(link).then(() => {}).catch(err => {
                console.error('Không thể sao chép liên kết: ', err);
                alert('Đã xảy ra lỗi khi sao chép liên kết!');
            });
        }

        // Hàm toggle khi click vào card để phóng to card
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

        // Hàm hiển thị chi tiết bài viết trong modal
        function showArticleDetails(title, content, date) {
            console.log(title, content, date); // Kiểm tra dữ liệu đầu vào
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalContent').innerText = content;
            document.getElementById('modalDate').innerText = date;

            const modal = document.getElementById('articleModal');
            modal.style.display = "block";
            modal.classList.add('show'); // Thêm lớp 'show' để hiển thị hiệu ứng
        }


        // Hàm đóng modal với hiệu ứng
        function closeModalWithEffect() {
            const modal = document.getElementById('articleModal');
            modal.classList.remove('show'); // Loại bỏ lớp 'show' để áp dụng hiệu ứng thu nhỏ
            setTimeout(() => {
                modal.style.display = "none"; // Ẩn modal sau khi hiệu ứng hoàn tất
            }, 300); // Thời gian trùng với thời gian hiệu ứng
        }
        // Hàm xử lý carousel
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
                currentIndex = (currentIndex === 0) ? totalCards - 3 : currentIndex - 1;
                updateCarousel();
            });

            nextButton.addEventListener("click", function() {
                currentIndex = (currentIndex === totalCards - 3) ? 0 : currentIndex + 1;
                updateCarousel();
            });

            // Ensure only 2 cards are visible at a time
            const containerWidth = 990; // Adjusted for larger cards (300px each + margin)
            document.querySelector('.carousel-container').style.width = `${containerWidth}px`;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const articleId = params.get('article');
            if (articleId) {
                // Tìm bài viết tương ứng
                const articleCard = document.querySelector(`.carousel-card[data-id="${articleId}"]`);
                if (articleCard) {
                    const title = articleCard.querySelector('.article-title').innerText;
                    const content = articleCard.querySelector('.article-content').innerText;
                    const date = articleCard.querySelector('.article-date').innerText;
                    const img = articleCard.querySelector('img').getAttribute('src');

                    // Mở modal
                    openHuongdanModal(articleCard, title, content, date, img);
                }
            }
        });
    </script>

    <hr style="border: none; border-top: 1px solid #ccc; margin: 20px auto; width: 50%;">
    <footer class="footer" id="contact" style="">
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

    @if(auth()->check())
    <a class="floating-button" id="openForm" href="{{ route('showFormRequest') }}"> </a>
    @else
    <!-- Nút nổi -->
    <div class="floating-button" id="openForm">
    </div>

    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal-registration" id="registrationForm">
        <h2>FORM YÊU CẦU</h2>
        <p style="color: red;">Hãy đăng nhập để được gửi yêu cầu hỗ trợ</p>
        <form id="registerForm">
            <div>
                <button type="button" onclick="window.location.href='{{ route('login') }}'">
                    Đăng nhập
                </button>
            </div>
        </form>
    </div>
    @endif
    <script>
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
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuItems = document.querySelectorAll("#navmenu ul li a");

            menuItems.forEach((item) => {
                item.addEventListener("click", function() {
                    // Loại bỏ lớp 'active' khỏi tất cả các mục menu
                    menuItems.forEach((link) => link.classList.remove("active"));

                    // Thêm lớp 'active' vào mục menu được nhấn
                    this.classList.add("active");
                });
            });

            // Gán active dựa trên vị trí trang khi load
            const currentLocation = window.location.href;
            menuItems.forEach((link) => {
                if (link.href === currentLocation) {
                    link.classList.add("active");
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>