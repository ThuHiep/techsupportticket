<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm mới bài viết</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/article/create.css') }}">
    <style>
        body .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
        body.mini-navbar .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
        .required {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Thêm mới bài viết</h1>
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="article_id" value="{{ $nextId }}">
        <div class="row mb-3">
            <div class="col-md-8">
                <!-- Tiêu đề -->
            <div class="form-group">
                <label for="title">Tiêu đề:<span class="required">*</span></label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title') }}"
                    required
                >
                @error('title')
                <div class="error visible">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="content">Nội dung:<span class="required">*</span></label>
                <textarea
                    name="content"
                    id="content"
                    rows="5"
                    required
                >{{ old('content') }}</textarea>
                @error('content')
                <div class="error visible">{{ $message }}</div>
                @enderror
            </div>
        </div>


            <div class="col-md-4 grouped-fields">
                <div class="container-img">
                    <div class="form-group">
                        <label for="profile_image" class="form-label profile-image-label">Chọn ảnh bài viết:</label>
                        <div class="custom-file-upload">
                            <input type="file" id="images" name="images" class="form-control" accept="images/*" onchange="previewImage(event)">
                            <label for="images" class="custom-file-label">Chọn mới</label>
                            <div class="image-preview">
                                <div id="image-preview" class="image-preview">
                                    <img id="preview-img" src="" alt="Image Preview" style="display:none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="button-group">
            <button type="submit" class="add-article-btn">Thêm mới</button>
            <a href="{{ route('articles.index') }}" class="cancel-btn">Quay lại</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleField = document.getElementById('title');
        const contentField = document.getElementById('content');

        // Bạn có thể thêm logic kiểm tra hoặc xử lý nội dung ở đây nếu cần
    });
</script>
<script>
    // Hàm xem trước ảnh
    function previewImage(event) {
        const imagePreview = document.getElementById('preview-img');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result; // Đặt ảnh được chọn làm src
                imagePreview.style.display = 'block'; // Hiển thị ảnh
            };
            reader.readAsDataURL(file); // Đọc dữ liệu ảnh
        } else {
            imagePreview.src = ''; // Xóa src nếu không có ảnh
            imagePreview.style.display = 'none'; // Ẩn ảnh
        }
    }
</script>

</body>
</html>
