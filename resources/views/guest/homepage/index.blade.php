<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Example</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: 'Poppins', Arial, sans-serif;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Canh giữa các thành phần trong header */
            background-color: #6F2F9F;
            padding: 15px 30px;
            color: black;
            position: sticky; /* Hoặc dùng position: fixed */
            top: 0; /* Gắn header ở đầu trang */
            z-index: 1000; /* Giúp header luôn nằm trên các phần tử khác */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Thêm hiệu ứng bóng */
        }

        .logo img {
            height: 40px;
        }
        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center; /* Align items in the same row */
        }
        .nav-links a {
            color: white;
            text-decoration: none !important; /* Remove underline */
            font-size: 17px; /* Updated font size */
            padding: 10px 0; /* Add padding to match button height */
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        .login-button {
            background-color: #6F2F9F;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 17px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .login-button:hover {
            transform: scale(1.05);
        }
        
        .main-content {
            background-color: #EAE8ED; /* Set body background color */
            display: flex;
            height: calc(100vh - 70px); /* Adjust height based on header */
            margin-bottom: 25px;
        }
        .main-content .left {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .main-content .left img {
            max-width: 90%; /* Increased size */
            height: auto;
        }
        .main-content .right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
           margin-left: 40px;
            padding: 20px;
        }
        .main-content .right h1 {
            font-size: 2em; /* Increased size */
            text-align: left;
            margin-bottom: 20px;
            line-height: 1.2;
            color: black; /* Set text color to black */
        }
        .main-content .right h1 span {
            display: block;
           font-size: 45px;
        }
        
        .main-content .right .search-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .main-content .right .search-container input[type="text"] {
            width: 80%;
            padding: 10px 15px; /* Adjust padding */
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 25px; /* Rounded corners */
            transition: border-color 0.3s ease;
        }
        .main-content .right .search-container input[type="text"]:focus {
            outline: none;
            border-color: #6F2F9F; /* Change border color on focus */
        }
        .main-content .right .search-container button {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #6F2F9F;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            font-size: 1.2em;
        }
        section {
            background-color: white;
        }
       
        .cards {
            display: flex;
            justify-content: center; /* Center các card */
            gap: 15px; /* Giảm khoảng cách giữa các card */
            margin-top: 20px;
        }
        .card {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            width: 200px;
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 1.2em;
            font-weight: bold;
            color: #764DAA;
            transition: transform 0.3s ease;
            margin: 0 5px; /* Khoảng cách hai bên mỗi card */
        }

        .card:hover {
            transform: scale(1.05);
        }
        .faq-title {
            text-align: center;
            color: #6F2F9F;
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 20px; /* Khoảng cách dưới tiêu đề */
        }

        .faq-container {
            display: flex; /* Sử dụng flexbox để chia thành hai cột */
            justify-content: space-between; /* Giãn đều giữa hai cột */
            gap: 20px; /* Khoảng cách giữa các cột */
            padding: 20px;
            margin: 20px 40px;
            border-radius: 10px;
            background-color: #fff;
        }

       

        .faq-list {
            flex: 2; /* Cột danh sách chiếm 1 phần */
            list-style-type: none; /* Xóa dấu đầu dòng */
            padding: 0;
        }

        .faq-list li {
            margin-bottom: 15px;
            font-size: 1em;
        }

        .faq-list a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .faq-list a:hover {
            color: #764DAA;
        }

        .faq-form-container {
            flex: 1; /* Cột form chiếm 1 phần */
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Canh trái */
            padding: 20px;
           
        }

        #ask-question-button {
            background-color: #6F2F9F;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        #ask-question-button:hover {
            background-color: #5b3e8c;
            transform: scale(1.05);
        }

        #question-form {
            display: none;
            width: 100%;
            margin-top: 20px;
        }

        #question-form input,
        #question-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #question-form button {
            background-color: #6F2F9F;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, background-color 0.3s ease;
        }

        #question-form button:hover {
            background-color: #5b3e8c;
            transform: scale(1.05);
        }


        /* Nút Gửi Câu Hỏi */
        #submit-question-button {
            background-color: #6F2F9F; /* Màu nền tím */
            color: white; /* Màu chữ trắng */
            border: none; /* Xóa viền */
            padding: 10px 20px; /* Khoảng cách nội dung */
            border-radius: 5px; /* Bo góc */
            cursor: pointer; /* Con trỏ chuột */
            font-size: 16px; /* Kích thước chữ */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Hiệu ứng đổ bóng */
            transition: transform 0.2s ease, background-color 0.3s ease; /* Hiệu ứng chuyển động */
            display: block; /* Đặt nút xuống hàng */
            margin-top: 10px; /* Tạo khoảng cách trên */
        }


        #submit-question-button:hover {
            background-color: #5b3e8c; /* Màu tím đậm hơn khi hover */
            transform: scale(1.05); /* Phóng to nhẹ khi hover */
        }
        .instructions {
            text-align: center;
            color: #6F2F9F;
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .carousel-container {
            position: relative;
            width: 80%;
            margin: 30px auto;
            overflow: hidden;
        }
        .carousel {
            display: flex;
            transition: transform 0.3s ease-in-out;
        }
        .carousel-card {
            min-width: 300px; /* Increased width */
            height: 300px; /* Increased height */
            margin: 15px; /* Increased margin */
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15); /* Slightly bigger shadow */
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.4em; /* Slightly larger font size */
            font-weight: bold;
            color: #6F2F9F;
        }
        .carousel-controls {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .carousel-button {
            background-color: #6F2F9F;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px; /* Increased size */
            height: 60px; /* Increased size */
            cursor: pointer;
            font-size: 2.5em; /* Larger font size */
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
            margin: 0 10px;
        }
        .carousel-button:hover {
            background-color: #5b3e8c;
        }
        footer{
            padding: 20px; /* Khoảng cách bên trong */
            margin: 20px 40px; /* Cách mép trái và phải 40px */
        }

       
    </style>
</head>
<body>
    
    
    <header>
        <div class="logo">
            <img src="guest/img/logosweetsoft.png" alt="Logo">
        </div>
        <nav class="nav-links">
            <a href="#home">Trang chủ</a>
            <a href="#faq">Bài viết</a>
            <a href="#ins">Hướng dẫn</a>
            <a href="#contact">Liên hệ</a>
            <button class="login-button">Đăng nhập</button>
        </nav>
    </header>
    <div class="main-content" id="home">
        <div class="left">
            <img src="guest/img/trangchu.png" alt="Sample Image">
        </div>
        <div class="right">
            <h1>
                <span >Chào mừng đến trang</span>
                <span>hỗ trợ khách hàng</span>
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
                <li><a href="#">Thời gian phản hồi từ đội hỗ trợ là bao lâu?</a></li>
                <li><a href="#">Tôi quên mật khẩu đăng nhập vào trang quản trị website, làm sao để khôi phục?</a></li>
                <li><a href="#">Website của tôi bị hack, tôi cần làm gì?</a></li>
                <li><a href="#">Tôi muốn cập nhật nội dung trên website, tôi phải làm thế nào?</a></li>
            </ul>
            <div class="faq-form-container">
                <button id="ask-question-button">Đặt câu hỏi</button>
                <div id="question-form">
                    <input id="question-name" type="text" placeholder="Nhập tên của bạn" />
                    <textarea id="question-text" placeholder="Nhập câu hỏi của bạn" rows="5"></textarea>
                    <button id="submit-question-button">Gửi</button>
                </div>
            </div>
        </div>
    </section>
    
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
                <div class="carousel-card">Card 1</div>
                <div class="carousel-card">Card 2</div>
                <div class="carousel-card">Card 3</div>
                <div class="carousel-card">Card 4</div>
                <div class="carousel-card">Card 5</div>
            </div>
            <div class="carousel-controls">
                <button class="carousel-button left" id="prev">&#8249;</button>
                <button class="carousel-button right" id="next">&#8250;</button>
            </div>
        </div>
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
                    <img src="guest/img/logosweetsoft.png" alt="SweetSoft Logo" style="width: 350px; margin-bottom: 10px;">
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
                    <h4>Sweetsoft trên Facebook</h4>
                    
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
        
        
</body>
</html>
