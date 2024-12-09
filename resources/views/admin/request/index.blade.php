<link rel="stylesheet" href="{{ asset('admin/css/request/index.css') }}">

<div class="container">
    <h1>Danh sách Yêu cầu Hỗ trợ Kỹ thuật</h1>
    <div class="top-bar">
        <a href="{{ route('request.create') }}" class="add-department-btn">Thêm mới</a>
        <div class="search-container">
            <form action="{{ route('request.index') }}" method="GET">
                <input type="text" name="search" placeholder="Nhập nội dung tìm kiếm (tiêu đề, mô tả)" value="{{ request()->query('search') }}">
                <select name="status">
                    <option value="">--Trạng thái--</option>
                    @foreach($statuses as $stt)
                        <option value="{{ $stt }}" {{ request()->query('status') == $stt ? 'selected' : '' }}>
                            {{ ucfirst($stt) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>
    </div>
    <div class="table-container">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>STT</th>
                <th>Mã yêu cầu</th>
                <th>Khách hàng</th>
                <th>Phòng ban</th>
                <th>Loại yêu cầu</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Ưu tiên</th>
                <th>Trạng thái</th>
                <th>Ngày nhận</th>
                <th>Ngày hoàn thành</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($requests as $index => $req)
                <tr>
                    <td>{{ ($requests->currentPage() - 1) * $requests->perPage() + $index + 1 }}</td>
                    <td>{{ $req->request_id }}</td>
                    <td>{{ $req->customer->full_name ?? 'N/A' }}</td>
                    <td>{{ $req->department->department_name ?? 'N/A' }}</td>
                    <td>{{ $req->requestType->request_type_name ?? 'N/A' }}</td>
                    <td>{{ $req->subject }}</td>
                    <td>{{ Str::limit($req->description, 50) }}</td>
                    <td>{{ ucfirst($req->priority) }}</td>
                    <td>
                        {{ ucfirst($req->status) }}
                        @if($req->status == 'completed')
                            <span class="status-dot completed"></span>
                        @elseif($req->status == 'processing')
                            <span class="status-dot processing"></span>
                        @elseif($req->status == 'cancelled')
                            <span class="status-dot cancelled"></span>
                        @elseif($req->status == 'handled')
                            <span class="status-dot handled"></span>
                        @endif
                    </td>
                    <td>{{ $req->received_at }}</td>
                    <td>{{ $req->resolved_at ?? 'N/A' }}</td>
                    <td>
                        <form action="{{ route('request.edit', $req->request_id) }}" style="display:inline;">
                            <button type="submit" class="edit-button">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                        </form>
                        <form action="{{ route('request.delete', $req->request_id) }}" method="POST" style="display:inline;" id="deleteForm{{ $req->request_id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-button" onclick="showDeleteModal(event, 'deleteForm{{ $req->request_id }}')">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination">
        {{ $requests->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function showDeleteModal(event, formId) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của nút

        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa yêu cầu này?',
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

