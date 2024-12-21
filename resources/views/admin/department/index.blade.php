<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách phòng ban</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    {{-- Thay đổi CSS phù hợp với department --}}
    <link rel="stylesheet" href="{{ asset('admin/css/department/index.css') }}">
</head>
<body>
<div class="container">
    <h1>Danh sách phòng ban</h1>
    <div class="top-bar">
        <a href="{{ route('department.create') }}" class="add-department-btn">Thêm mới</a>
        <div class="search-container">
            <form action="{{ route('department.index') }}" method="GET">
                <!-- Ô nhập tìm kiếm tên hoặc mã phòng ban -->
                <div style="position: relative;">
                    <input type="text" name="search" placeholder="Nhập tên hoặc mã phòng ban cần tìm" value="{{ request()->query('search') }}">
                    @if($search)
                    <a
                        href="{{ route('department.index') }}"
                        id="clearButton"
                        style="position: absolute; right: 3%; top: 50%; transform: translateY(-50%); text-decoration: none; color: #D5D5D5; font-size: 18px; cursor: pointer;">
                        ✖
                    </a>
                    @endif
                </div>
                <button type="submit" style="padding: 10.9px 15px">Tìm kiếm</button>
            </form>
        </div>
    </div>

    {{-- Hiển thị thông báo tìm kiếm --}}
    @if ($searchPerformed && $search !== '')
        @if ($count > 0)
            @if (preg_match('/^PB\d{4}$/', $search))
                <div id="search-notification"  class="alert-success" style="text-align: center; color: green; margin-bottom: 15px;">
                    Tìm thấy {{ $count }} phòng ban có mã: "{{ $search }}"
                </div>
            @else
                <div  id="search-notification" class="alert-success" style="text-align: center; color: green; margin-bottom: 15px;">
                    Tìm thấy {{ $count }} phòng ban có từ khóa: "{{ $search }}"
                </div>
            @endif
        @else
            @if (preg_match('/^PB\d{4}$/', $search))
                <div  id="search-notification"  class="alert-danger" style="text-align: center; color: red; margin-bottom: 15px;">
                    Không tìm thấy phòng ban có mã: "{{ $search }}"
                </div>
            @else
                <div  id="search-notification"  class="alert-danger" style="text-align: center; color: red; margin-bottom: 15px;">
                    Không tìm thấy phòng ban có từ khóa: "{{ $search }}"
                </div>
            @endif
        @endif
    @endif


    <div class="table-container">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>STT</th>
                <th>Mã phòng ban</th>
                <th>Tên phòng ban</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @if ($departments->isEmpty())
                <tr>
                    <td colspan="5" style="text-align: center;">Không có dữ liệu để hiển thị.</td>
                </tr>
            @else
                @foreach ($departments as $index => $department)
                    <tr>
                        <td>{{ ($departments->currentPage() - 1) * $departments->perPage() + $index + 1 }}</td>
                        <td>{{ $department->department_id }}</td> <!-- Thay đổi từ department_id sang department_id -->
                        <td>{{ $department->department_name }}</td>
                        <td>
                            @if($department->status == 'active')
                                <span class="status-dot active"></span> Hoạt động
                            @else
                                <span class="status-dot inactive"></span> Không hoạt động
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('department.edit', $department->department_id) }}" style="display:inline;">
                                <button type="submit" class="edit-button" title="Sửa phòng ban">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <form action="{{ route('department.delete', $department->department_id) }}" method="POST" style="display:inline;" id="deleteForm{{ $department->department_id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-button" onclick="showDeleteModal(event, 'deleteForm{{ $department->department_id }}')">
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
        {{ $departments->links('pagination::bootstrap-4') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>// Tự động ẩn thông báo tìm kiếm sau 5 giây
    setTimeout(function() {
        var searchNotification = document.getElementById('search-notification');
        if (searchNotification) {
            searchNotification.style.transition = 'opacity 0.5s ease-out';
            searchNotification.style.opacity = '0';
            setTimeout(() => searchNotification.style.display = 'none', 500); // Ẩn hoàn toàn sau hiệu ứng mờ dần
        }
    }, 3000);
</script>
<script>
    function showDeleteModal(event, formId) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của nút

        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa phòng ban này?',
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
