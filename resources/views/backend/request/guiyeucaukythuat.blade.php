
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="/techsupportticket/public/backend/css/guiyeucaukythuat.css?v=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <select id="request-type" name="request-type" class="input-field" required>
                            <option value="" disabled selected>Chọn loại yêu cầu</option>
                            <option value="software">Hỗ trợ phần mềm</option>
                            <option value="hardware">Hỗ trợ phần cứng</option>
                            <option value="network">Hỗ trợ mạng</option>
                        </select>
                        <label for="request-type" class="label">Loại yêu cầu</label>
                    </div>
                    <div class="input_box">
                        <input type="text" id="company-name" name="company-name" class="input-field" required>
                        <label for="company-name" class="label">Tên công ty</label>
                        <i class="bx bx-buildings icon"></i>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="input_box description-box">
                    <textarea id="description" name="description" class="input-field description-field" rows="10" required></textarea>
                    <label for="description" class="label">Mô tả vấn đề</label>
                    <i class="bx bx-message-dots icon"></i>
                </div>
                <!-- Row 5: File upload -->
                <div class="input_box file-upload-box">
                    <label for="support-file" class="file-upload-label">Tải lên ảnh hỗ trợ:</label>
                    <input type="file" id="support-file" name="support-file" class="file-upload-field" accept="image/*">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="input_box">
                <input type="submit" class="input-submit" value="Gửi Yêu Cầu">
            </div>
        </form>

        <!-- Return Link -->
        <div class="login-link">
            <span>Quay lại trang chủ? <a href="index.html" class="login-box">Bấm vào đây</a></span>
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
</body>
</html>
