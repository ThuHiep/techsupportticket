<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách yêu cầu hỗ trợ Kỹ thuật</title>
    <link rel="stylesheet" href="{{ asset('admin/css/request/index.css') }}">
    <!-- Font Awesome CDN để hiển thị biểu tượng -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container">
    <h1>Danh sách yêu cầu hỗ trợ Kỹ thuật</h1>
    <div class="top-bar">
        <!-- Container cho thanh tìm kiếm -->
        <div class="search-container">
            <form action="{{ route('request.index') }}" method="GET" class="search-form">
                <!-- Phần Tìm Kiếm Chính -->
                <div class="primary-search">
                    <!-- Ô input tìm kiếm Subject -->
                    <input type="text" name="subject" id="subject" placeholder="Nhập tiêu đề" value="{{ request()->query('subject') }}">

                    <!-- Select chọn trường tìm kiếm bổ sung -->
                    <select name="search_field" id="search_field">
                        <option value="">--Chọn tiêu chí cần tìm kiếm--</option>
                        <option value="customer" {{ request()->query('search_field') == 'customer' ? 'selected' : '' }}>Tên khách hàng</option>
                        <option value="department" {{ request()->query('search_field') == 'department' ? 'selected' : '' }}>Phòng ban</option>
                        <option value="request_date" {{ request()->query('search_field') == 'request_date' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="status" {{ request()->query('search_field') == 'status' ? 'selected' : '' }}>Trạng thái</option>
                    </select>
                </div>

                <!-- Phần Tìm Kiếm Bổ Sung -->
                <div class="additional-search">
                    <!-- Tìm kiếm theo Khách hàng -->
                    <div class="search-field" id="search_customer">
                        <select name="customer_id">
                            <option value="">--Khách hàng--</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->customer_id }}" {{ request()->query('customer_id') == $customer->customer_id ? 'selected' : '' }}>
                                    {{ $customer->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tìm kiếm theo Phòng ban -->
                    <div class="search-field" id="search_department">
                        <select name="department_id">
                            <option value="">--Phòng ban tiếp nhận--</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->department_id }}" {{ request()->query('department_id') == $department->department_id ? 'selected' : '' }}>
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tìm kiếm theo Ngày tạo -->
                    <div class="search-field" id="search_request_date">
                        <input type="date" name="request_date_search" placeholder="Chọn ngày tạo" value="{{ request()->query('request_date_search') }}">
                    </div>

                    <!-- Tìm kiếm theo Trạng thái -->
                    <div class="search-field" id="search_status">
                        <select name="status_search">
                            <option value="">--Trạng thái--</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request()->query('status_search') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Nút Tìm kiếm -->
                <button type="submit" class="search-button">Tìm kiếm</button>
            </form>
        </div>
    </div>

    <!-- Thông báo kết quả tìm kiếm -->
    @if ($searchPerformed && ($search !== '' || $additionalSearchValue !== null))
        @if ($count > 0)
            <div class="alert-success" style="text-align: center; color: green; margin-bottom: 15px;">
                Tìm thấy {{ $count }} yêu cầu hỗ trợ
                @if ($search !== '' && $additionalSearchValue !== null)
                    có tiêu đề "{{ $search }}" và
                    @if ($additionalSearchType == 'customer')
                        của khách hàng "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'department')
                        thuộc phòng ban "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'request_date')
                        được tạo vào ngày "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'status')
                        có trạng thái "{{ $additionalSearchValue }}"
                    @endif
                @elseif ($search !== '')
                    có tiêu đề "{{ $search }}"
                @elseif ($additionalSearchValue !== null)
                    @if ($additionalSearchType == 'customer')
                        của khách hàng "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'department')
                        thuộc phòng ban "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'request_date')
                        được tạo vào ngày "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'status')
                        có trạng thái "{{ $additionalSearchValue }}"
                    @endif
                @endif
            </div>
        @else
            <div class="alert-danger" style="text-align: center; color: red; margin-bottom: 15px;">
                Không tìm thấy yêu cầu hỗ trợ nào
                @if ($search !== '' && $additionalSearchValue !== null)
                    có tiêu đề "{{ $search }}" và
                    @if ($additionalSearchType == 'customer')
                        thuộc khách hàng "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'department')
                        thuộc phòng ban "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'request_date')
                        được tạo vào ngày "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'status')
                        có trạng thái "{{ $additionalSearchValue }}"
                    @endif
                @elseif ($search !== '')
                    có tiêu đề "{{ $search }}"
                @elseif ($additionalSearchValue !== null)
                    @if ($additionalSearchType == 'customer')
                        thuộc khách hàng "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'department')
                        thuộc phòng ban "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'request_date')
                        được tạo vào ngày "{{ $additionalSearchValue }}"
                    @elseif ($additionalSearchType == 'status')
                        có trạng thái "{{ $additionalSearchValue }}"
                    @endif
                @endif
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
                <th>Ngày tạo</th>
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
                
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">
        {{ $requests->links('pagination::bootstrap-4') }}
    </div>
</div>

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

    // JavaScript để điều khiển hiển thị các ô input tìm kiếm bổ sung
    document.addEventListener('DOMContentLoaded', function() {
        const searchFieldSelect = document.getElementById('search_field');
        const additionalSearchFields = document.querySelectorAll('.additional-search .search-field');

        function updateSearchFields() {
            const selectedField = searchFieldSelect.value;

            // Ẩn tất cả các trường tìm kiếm bổ sung
            additionalSearchFields.forEach(field => {
                field.style.display = 'none';
            });

            // Hiển thị trường tìm kiếm tương ứng nếu có lựa chọn
            if (selectedField) {
                const fieldToShow = document.getElementById(`search_${selectedField}`);
                if (fieldToShow) {
                    fieldToShow.style.display = 'block';
                }
            }
        }

        // Gọi hàm khi trang được tải lần đầu
        updateSearchFields();

        // Gọi hàm khi chọn thay đổi
        searchFieldSelect.addEventListener('change', updateSearchFields);
    });
</script>
</body>
</html>
