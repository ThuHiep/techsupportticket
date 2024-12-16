<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('admin/css/employee/styles.css') }}">
<style>
    /* Khi sidebar ở trạng thái bình thường */
    body .container {
        width: calc(98%);
        /* Độ rộng sau khi trừ sidebar */
        transition: all 0.3s ease-in-out;
    }

    /* Khi sidebar thu nhỏ */
    body.mini-navbar .container {
        width: calc(98%);
        /* Mở rộng nội dung khi sidebar thu nhỏ */
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
                        placeholder="Nhập mã nhân viên hoặc tên nhân viên cần tìm"
                        value="{{ $search }}"
                        style="padding-right: 30px;">
                    @if($search)
                    <a
                        href="{{ route('employee.index') }}"
                        id="clearButton"
                        style="position: absolute; right: 22%; top: 50%; transform: translateY(-50%); text-decoration: none; color: #D5D5D5; font-size: 18px; cursor: pointer;">
                        ✖
                    </a>
                    @endif
                </div>
                <button type="submit">Tìm kiếm</button>
            </form>
        </div>


        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã nhân viên</th>
                        <th>Tên người dùng</th>
                        {{-- <th>Tên tài khoản</th> --}}
                        <th>Ảnh đại diện</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $idx => $employee)
                    <tr class="text-center">
                        <td>{{ $employees->firstItem() + $idx }}</td>
                        <td>{{ $employee->employee_id }}</td>
                        <td>{{ $employee->full_name }}</td>
                        {{-- <td>{{ $employee->username }}</td> --}}
                        <td>
                            <img src="{{$employee->profile_image ? asset('admin/img/employee/' .  $employee->profile_image) : asset('admin/img/customer/default.png') }}" alt="Hình ảnh nhân viên" class="employee-image">
                        </td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->description }}</td>

                        <td>
                            @if($employee->status == 'active')
                            <span class="status-dot active"></span> Đang kích hoạt
                            @else
                            <span class="status-dot inactive"></span> Ngừng kích hoạt
                            @endif
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
        <div class="d-flex justify-content-center pagination">
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
<script>
    function deleteConfirm(event, formId) {
        event.preventDefault(); // Ngăn chặn hành động mặc định của nút

        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa nhân viên này?',
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

    // Thông báo thành công
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
    // Thông báo thành công
    @if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Tìm kiếm không thành công',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
    @endif
</script>