<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('admin/css/employee/styles.css') }}">
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
<body>
    <section class="container">
        <!-- Default box -->
        <div class="card-header align-items-center justify-content-between">
            <h1>Danh sách nhân viên</h1>
            <a href="{{ route('employee.create') }}" class="add-customer-btn">Thêm mới</a>
        </div>
        <div class="search-container">
            <form action="{{ route('employee.index') }}" method="GET">
                <div style="position: relative;">
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        placeholder="Nhập tên nhân viên cần tìm"
                        value="{{ $search }}"
                        style="padding-right: 30px;">
                    @if($search)
                    <a
                        href="{{ route('employee.index') }}"
                        id="clearButton"
                        style="position: absolute; right: 22%; top: 50%; transform: translateY(-50%); text-decoration: none; color: black; font-size: 18px; cursor: pointer;">
                        ✖
                    </a>
                    @endif
                </div>
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>
    
    
        <div class="table-container">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
    
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên người dùng</th>
                        <th>Tên tài khoản</th>
                        <th>Ảnh đại diện</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr class="text-center">
                        <td>{{ $employee->user_id }}</td>
                        <td>{{ $employee->full_name }}</td>
                        <td>{{ $employee->username }}</td>
                        <td>
                            <img src="{{ asset('admin/img/employee/' . $employee->profile_image) }}" alt="Hình ảnh khách hàng" class="employee-image">
                        </td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->role_name }}</td>
                        <td>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ $employee->status   == 'active' ? 'selected' : '' }}>Đang kích hoạt</option>
                                <option value="inactive" {{ $employee->status  == 'inactive' ? 'selected' : '' }}>Ngừng kích hoạt</option>
                            </select>
                        </td>
                        <td>
                            <form action="{{ route('employee.edit', $employee->employee_id) }}" style="display:inline;">
                                <button type="submit" class="edit-button">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <form action="{{ route('employee.delete', $employee->user_id ) }}" method="POST" style="display:inline;" id="deleteForm{{ $employee->user_id  }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-button" onclick="deleteConfirm(event, 'deleteForm{{ $employee->user_id  }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $employees->links('pagination::bootstrap-4') }}
        </div>
    
    </section>
</body>

<!-- Main content -->
<script>
    function deleteConfirm(event, formId) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của nút

        if (confirm('Bạn có chắc chắn muốn xóa nhân viên này? Hành động này không thể hoàn tác!')) {
            document.getElementById(formId).submit(); // Thực hiện xóa nếu người dùng xác nhận
        }
    }
</script>