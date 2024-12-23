<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa bài viết</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/article/edit.css') }}">
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
    <h1>Chỉnh sửa bài viết</h1>
    @if(session('success'))
        <div class="alert alert-success" style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('articles.update', $article->article_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Dùng PUT để cập nhật -->

        <div class="row mb-3">
            <div class="col-md-8">
                <!-- Mã bài viết -->
                <div class="form-group">
                    <label for="article_id">Mã bài viết:<span class="required">*</span></label>
                    <input
                        type="text"
                        name="article_id"
                        id="article_id"
                        value="{{ $article->article_id }}"
                        readonly
                    >
                </div>

                <!-- Tiêu đề -->
                <div class="form-group">
                    <label for="title">Tiêu đề:<span class="required">*</span></label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $article->title) }}"
                        required
                    >
                </div>

                <!-- Nội dung -->
                <div class="form-group">
                    <label for="content">Nội dung:<span class="required">*</span></label>
                    <textarea
                        name="content"
                        id="content"
                        rows="5"
                        required
                    >{{ old('content', $article->content) }}</textarea>
                </div>
            </div>

            <div class="col-md-4 grouped-fields">
                <!-- Ảnh bài viết -->
                <div class="container-img">
                        <label for="images" class="form-label profile-image-label" style="font-size:18px">Ảnh bài viết:</label>
                        <div class="custom-file-upload">
                            <input type="file" id="images" name="images" class="form-control" accept="images/*" onchange="previewImage(event)">
                            <label for="images" class="custom-file-label">Chọn khác</label>
                            <div class="image-preview">
                                <div id="image-preview" class="image-preview">
                                    <img id="preview-img" src="{{ asset('admin/img/articles/' . $article->images) }}" alt="Image Preview" style="display: {{ $article->images ? 'block' : 'none' }};">
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="add-article-btn">Cập nhật</button>
            <a href="{{ route('articles.index') }}" class="cancel-btn">Hủy</a>
        </div>
    </form>
</div>

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
