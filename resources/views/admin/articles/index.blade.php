<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách bài hướng dẫn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/faq/index.css') }}">
    <style>
        body .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }

        body.mini-navbar .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Danh sách bài hướng dẫn</h1>
    <div class="top-bar">
        <a href="{{ route('articles.create') }}" class="add-faq-btn">Thêm mới</a>
        <div class="search-container">
            <form action="{{ route('articles.index') }}" method="GET" >
                <!-- Input tìm kiếm -->
                <div style="position: relative; flex: 2;">
                    <input type="text" name="search" placeholder="Nhập mã hoặc tiêu đề bài hướng dẫn" value="{{ request()->query('search') }}">
                    @if(request()->query('search'))
                    <!-- Biểu tượng X -->
                    <a href="{{ route('articles.index') }}"
                        id="clearButton"
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); text-decoration: none; color: #D5D5D5; font-size: 18px; cursor: pointer;">
                        ✖
                    </a>
                    @endif
                </div>
                <input type="date" id="searchDate" name="date" placeholder="Tìm kiếm theo ngày" value="{{ request('date') }}" />
                <!-- Nút tìm kiếm -->
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>
    </div>
    @if($search || $date)
        @if($totalResults > 0)
            <div class="alert alert-success" style="text-align: center; color: green; margin-top: 10px;">
                @if($search && preg_match('/^ART\d{4}$/', $search))
                    Tìm thấy bài viết với mã "{{ $search }}"
                @elseif($search && $date)
                    Tìm thấy {{ $totalResults }} bài viết chứa từ khóa "{{ $search }}" vào ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                @elseif($search)
                    Tìm thấy {{ $totalResults }} bài viết chứa từ khóa "{{ $search }}"
                @else
                    Tìm thấy {{ $totalResults }} bài viết vào ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                @endif
            </div>
        @else
            <div class="alert alert-danger" style="text-align: center; color: red; margin-top: 10px;">
                @if($search && preg_match('/^ART\d{4}$/', $search))
                    Không tìm thấy bài viết với mã "{{ $search }}"
                @elseif($search && $date)
                    Không tìm thấy bài viết chứa từ khóa "{{ $search }}" vào ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                @elseif($search)
                    Không tìm thấy bài viết chứa từ khóa "{{ $search }}"
                @else
                    Không tìm thấy bài viết vào ngày {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                @endif
            </div>
        @endif
    @endif



    <!-- Bảng danh sách bài viết -->
    <div class="table-container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã bài viết</th>
                    <th>Tiêu đề</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($articles as $index => $article)
                <tr>
                    <td>{{ ($articles->currentPage() - 1) * $articles->perPage() + $index + 1 }}</td>
                    <td>{{ $article->article_id }}</td>
                    <td>{{ Str::limit($article->title, 50) }}</td>
{{--                    <td>--}}
{{--                        @if($article->images)--}}
{{--                            <img src="{{ asset('admin/img/articles/' . $article->images) }}" alt="Image" style="width: 100px; height: auto; object-fit: cover;">--}}
{{--                        @else--}}
{{--                            Không có ảnh--}}
{{--                        @endif--}}
{{--                    </td>--}}
                    <td>{{ \Carbon\Carbon::parse($article->create_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        <!-- Nút sửa -->
                        <form action="{{ route('articles.edit', $article->article_id) }}" style="display:inline;">
                            <button type="submit" class="edit-button">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>
                        <!-- Nút xóa -->
                        <form action="{{ route('articles.delete', $article->article_id) }}" method="POST" style="display:inline;" id="deleteForm{{ $article->article_id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-button" onclick="showDeleteModal(event, 'deleteForm{{ $article->article_id }}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="pagination">
        {{ $articles->links('pagination::bootstrap-4') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    setTimeout(function() {
    const alertBox = document.querySelector('.alert');
    if (alertBox) {
        alertBox.style.transition = 'opacity 0.5s ease-out';
        alertBox.style.opacity = '0';
        setTimeout(() => alertBox.remove(), 500);
    }
}, 3000);

</script>
    <script>
        function showDeleteModal(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa câu hỏi này?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

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
    </script>
</body>

</html>
