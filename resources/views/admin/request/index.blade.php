<link rel="stylesheet" href="{{ asset('admin/css/request/index.css') }}">
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
    .required {
        color: red;
        font-size: 14px;
    }
</style>
<body>
    <div class="container">
        <h1>Danh sách yêu cầu hỗ trợ Kỹ thuật</h1>
        <div class="top-bar">
            <!-- Xóa bỏ nút "Thêm mới" -->
            <!-- Chèn các trường tìm kiếm vào vị trí này -->
            <div class="search-container">
                <form action="{{ route('request.index') }}" method="GET">
                    <!-- Dropdown Khách hàng -->
                    <select name="customer_id">
                        <option value="">--Khách hàng--</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->customer_id }}" {{ request()->query('customer_id') == $customer->customer_id ? 'selected' : '' }}>
                                {{ $customer->full_name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Dropdown Phòng ban -->
                    <select name="department_id">
                        <option value="">--Phòng ban tiếp nhận--</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->department_id }}" {{ request()->query('department_id') == $department->department_id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Input Ngày nhận -->
                    <input type="date" name="request_date" placeholder="Ngày nhận yêu cầu" value="{{ request()->query('request_date') }}">

                    <!-- Dropdown Trạng thái -->
                    <select name="status">
                        <option value="">--Trạng thái--</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request()->query('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Nút Tìm kiếm -->
                    <button type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>

        <!-- Thông báo kết quả tìm kiếm -->
        @if (request()->filled('customer_id') || request()->filled('department_id') || request()->filled('request_date') || request()->filled('status'))
            @if ($count > 0)
                <div class="alert alert-success">
                    Đã tìm thấy {{ $count }} yêu cầu hỗ trợ phù hợp với các tiêu chí tìm kiếm.
                </div>
            @else
                <div class="alert alert-danger">
                    Không tìm thấy yêu cầu hỗ trợ nào phù hợp với các tiêu chí tìm kiếm.
                </div>
            @endif
        @endif

        <div class="table-container">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Khách hàng</th>
                    <th>Loại yêu cầu</th>
                    <th>Tiêu đề</th>
                    <th>Ưu tiên</th>
                    <th>Ngày nhận</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($requests as $index => $req)
                    <tr>
                        <td>{{ ($requests->currentPage() - 1) * $requests->perPage() + $index + 1 }}</td>
                        <td>{{ $req->customer->full_name ?? 'N/A' }}</td>
                        <td>{{ $req->requestType->request_type_name ?? 'N/A' }}</td>
                        <td>{{ $req->subject }}</td>
                        <td>{{ $req->priority }}</td>
                        <td>{{ \Carbon\Carbon::parse($req->create_at)->format('d/m/Y') }}</td>
                        <td>
                            {{ $req->status }}
                            <span class="status-dot
                                @if($req->status == 'Chưa xử lý') chưa-xử-lý
                                @elseif($req->status == 'Đang xử lý') đang-xử-lý
                                @elseif($req->status == 'Hoàn thành') hoàn-thành
                                @elseif($req->status == 'Đã hủy') đã-hủy
                                @endif
                            " title="{{ $req->status }}"></span>
                        </td>
                        <td>
                            <form action="{{ route('request.edit', $req->request_id) }}" style="display:inline;">
                                <button type="submit" class="edit-button" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <form action="{{ route('request.delete', $req->request_id) }}" method="POST" style="display:inline;" id="deleteForm{{ $req->request_id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-button" onclick="showDeleteModal(event, 'deleteForm{{ $req->request_id }}')" title="Xóa">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-danger">Không tìm thấy yêu cầu hỗ trợ nào phù hợp với các tiêu chí tìm kiếm.</td>
                    </tr>
                @endforelse
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

    @if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Thất bại!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
    @endif
</script>
</body>
