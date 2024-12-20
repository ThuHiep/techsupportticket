<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('admin/css/form/guiyeucaukythuat.css') }}">
    <title>FORM</title>
</head>

<body>
    <!-- Form -->
    <div class="wrapper">
        <div class="support_box">
            <div class="support-header">
                <span>Gửi Yêu Cầu Hỗ Trợ</span>
            </div>

            <!-- Thời gian hiện tại -->
            <div class="date-time-container">
                <div class="date-time" id="current-datetime"></div>
            </div>

            <!-- Form Input -->
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="input-container">
                    <!-- Row 1 -->
                    <div class="input_row">
                        <div class="input_box">
                            <input type="text" id="fullname" name="fullname" class="input-field" required>
                            <label for="fullname" class="label">Họ và tên</label>
                            <i class="bx bx-user icon"></i>
                        </div>
                        <div class="input_box">
                            <input type="tel" id="phone" name="phone" class="input-field" pattern="[0-9]{10}" required>
                            <label for="phone" class="label">Số điện thoại</label>
                            <i class="bx bx-phone icon"></i>
                        </div>
                    </div>
                    <!-- Row 2 -->
                    <div class="input_box">
                        <input type="email" id="email" name="email" class="input-field" required>
                        <label for="email" class="label">Email</label>
                        <i class="bx bx-envelope icon"></i>
                    </div>
                    <!-- Row 3 -->
                    <div class="input_row">
                        <div class="input_box">
                            <input type="text" id="company-name" name="company-name" class="input-field" required>
                            <label for="company-name" class="label">Tên công ty</label>
                            <i class="bx bx-buildings icon"></i>
                        </div>
                        <div class="input_box">
                            <select id="request-type" name="request-type" class="input-field" required>
                                <option value="" disabled selected>Chọn loại yêu cầu</option>
                            </select>
                            <label for="request-type" class="label">Loại yêu cầu</label>
                        </div>
                    </div>

                    <!-- Row 4 -->
                    <!-- Thông tin yêu cầu -->
                    <div class="request-info-row">
                        <span class="request-info-text">Thông tin yêu cầu</span>
                        <button type="button" class="add-btn">+ Thêm</button>
                    </div>

                    <!-- Form Input -->
                    <div id="request-container">
                        <!-- Khối mặc định ban đầu -->
                        <div class="request-block">
                            <div class="input_box input-title-1">
                                <input type="text" id="title-1" name="title[]" class="input-field" required>
                                <label for="title-1" class="label">Tiêu đề</label>
                            </div>

                            <div class="input_box description-box">
                                <textarea id="description-1" name="description[]" class="input-field description-field" rows="10" required></textarea>
                                <label for="description-1" class="label">Mô tả vấn đề</label>
                            </div>

                            <div class="input_box file-upload-box">
                                <label for="support-file-1" class="file-upload-label">Tải lên ảnh hỗ trợ:</label>
                                <input type="file" id="support-file-1" name="support-file[]" class="file-upload-field" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="input_box">
                    <input type="submit" class="input-submit" value="Gửi Yêu Cầu">
                </div>
            </form>

            <!-- Return Link -->
            <div class="login-link">
                <span>Quay lại trang chủ? <a href="{{route('homepage.index')}}" class="login-box">Bấm vào đây</a></span>
            </div>
        </div>
    </div>


    <!-- JavaScript hiển thị thời gian -->
    <script>
        const dateTimeElement = document.getElementById("current-datetime");

        function updateDate() {
            const now = new Date();
            const formattedDate = now.toLocaleDateString("vi-VN", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric",
            });
            dateTimeElement.textContent = `Ngày: ${formattedDate}`;
        }
        updateDate();
    </script>
    <!-- Script thêm khối -->
    <script>
        // Hàm thêm khối mới
        function addRequestBlock() {
            // Tạo khối mới
            const newBlock = document.createElement("div");
            newBlock.classList.add("request-block", "hidden"); // Khởi tạo khối mới ở trạng thái ẩn
            newBlock.innerHTML = `
        <div class="input_box input-title-1">
            <input type="text" name="title[]" class="input-field" required>
            <label class="label">Tiêu đề</label>
        </div>

        <div class="input_box description-box">
            <textarea name="description[]" class="input-field description-field" rows="10" required></textarea>
            <label class="label">Mô tả vấn đề</label>
        </div>

        <div class="input_box file-upload-box">
            <label class="file-upload-label">Tải lên ảnh hỗ trợ:</label>
            <input type="file" name="support-file[]" class="file-upload-field" accept="image/*">
            <!-- Nút + Thêm từ khối thứ 2 trở đi -->
            <button type="button" class="add-btn-inline" onclick="addRequestBlock()">+ Thêm</button>
            <button type="button" class="delete-btn" onclick="removeRequestBlock(this)">Xóa</button>
        </div>
    `;

            // Thêm khối mới vào container
            const container = document.getElementById("request-container");
            container.appendChild(newBlock);

            // Hiện khối mới với hiệu ứng
            setTimeout(() => newBlock.classList.remove("hidden"), 10);
        }

        // Hàm xóa khối
        function removeRequestBlock(button) {
            const block = button.closest(".request-block");

            // Bắt đầu hiệu ứng ẩn với CSS
            block.classList.add("hidden");

            // Sau khi hiệu ứng ẩn hoàn thành, xóa khối khỏi DOM
            setTimeout(() => block.remove(), 300); // Thời gian đồng bộ với hiệu ứng (0.3s)
        }
        // Gán sự kiện cho nút Thêm
        document.querySelector(".add-btn").addEventListener("click", addRequestBlock);
    </script>
</body>

</html>