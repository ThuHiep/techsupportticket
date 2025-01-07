<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách loại yêu cầu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/department/index.css') }}">
</head>
<body>
<div class="container">
    <h1>Danh sách loại yêu cầu</h1>
    <div class="top-bar">
        <a href="{{ route('requesttype.create') }}" class="add-department-btn">Thêm mới</a>
        <div class="search-container">
            <form action="{{ route('requesttype.index') }}" method="GET">
                <div style="position: relative;">
                    <input type="text" name="search" placeholder="Nhập tên loại yêu cầu cần tìm" value="{{ request()->query('search') }}">
                    @if(request()->query('search'))
                        <a href="{{ route('requesttype.index') }}" id="clearButton" style="position: absolute; right: 3%; top: 50%; transform: translateY(-50%); text-decoration: none; color: #D5D5D5; font-size: 18px; cursor: pointer;">✖</a>
                    @endif
                </div>
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>
    </div>

    {{-- Hiển thị thông báo tìm kiếm --}}
    @if ($searchPerformed && $search !== '')
        <div id="search-notification" class="alert {{ $count > 0 ? 'alert-success' : 'alert-danger' }}" style="text-align: center; margin-bottom: 15px;">
            {{ $count > 0 ? "Tìm thấy $count loại yêu cầu có từ khóa: \"$search\"" : "Không tìm thấy loại yêu cầu có từ khóa: \"$search\"" }}
        </div>
    @endif

    <div class="table-container">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>STT</th>
                <th>Tên loại yêu cầu</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @if ($requesttypes->isEmpty())
                <tr>
                    <td colspan="4" style="text-align: center;">Không có dữ liệu để hiển thị.</td>
                </tr>
            @else
                @foreach ($requesttypes as $index => $requesttype)
                    <tr>
                        <td>{{ ($requesttypes->currentPage() - 1) * $requesttypes->perPage() + $index + 1 }}</td>
                        <td>{{ $requesttype->request_type_name }}</td>
                        <td>
                            <form action="{{ route('requesttype.delete', $requesttype->request_type_id) }}" method="POST" style="display:inline;" id="deleteForm{{ $requesttype->request_type_id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-button" onclick="showDeleteModal(event, 'deleteForm{{ $requesttype->request_type_id}}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="pagination">
        {{ $requesttypes->links('pagination::bootstrap-4') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Tự động ẩn thông báo tìm kiếm sau 5 giây
    setTimeout(function() {
        var searchNotification = document.getElementById('search-notification');
        if (searchNotification) {
            searchNotification.style.transition = 'opacity 0.5s ease-out';
            searchNotification.style.opacity = '0';
            setTimeout(() => searchNotification.style.display = 'none', 500);
        }
    }, 3000);

    function showDeleteModal(event, formId) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của nút

        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa loại yêu cầu này?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit(); // Thực hiện xóa nếu người dùng xác nhận
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
