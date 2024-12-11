<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách phòng ban</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    {{-- Thay đổi CSS phù hợp với department --}}
    <link rel="stylesheet" href="{{ asset('admin/css/department/index.css') }}">
    <style>
        /* Khi sidebar ở trạng thái bình thường */
        body .container {
            width: calc(98%); /* Độ rộng sau khi trừ sidebar */
            transition: all 0.3s ease-in-out;
        }

        /* Khi sidebar thu nhỏ */
        body.mini-navbar .container {
            width: calc(98%); /* Mở rộng nội dung khi sidebar thu nhỏ */
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="wrapper wrapper-content">
        <div class="container">
            <h1>Danh sách phòng ban</h1>
            <div class="top-bar">
                <a href="{{ route('department.create') }}" class="add-department-btn">Thêm mới</a>
                <div class="search-container">
                    <form action="{{ route('department.index') }}" method="GET">
                        <input type="text" name="search" placeholder="Nhập tên phòng ban cần tìm" value="{{ request()->query('search') }}">
                        <button type="submit">Tìm kiếm</button>
                    </form>
                </div>
            </div>
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
                    @foreach ($departments as $index => $department)
                        <tr>
                            <td>{{ ($departments->currentPage() - 1) * $departments->perPage() + $index + 1 }}</td>
                            <td>{{ $department->department_id }}</td>
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
                                    <button type="submit" class="edit-button">
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
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                {{ $departments->links('pagination::bootstrap-4') }}
            </div>
        
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
