
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('admin/css/employee/styles.css') }}">
<style>
    .modal.show {
        display: block !important;
        opacity: 1 !important;
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
                <input type="text" name="search" placeholder="Nhập tên nhân viên cần tìm" value="{{ request()->query('search') }}">
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
                            <td>{{ $employee->status }}</td>
                            <td>
                                <form action="{{ route('customer.edit', $employee->employee_id) }}" style="display:inline;">
                                    <button type="submit" class="edit-button">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                <form action="{{ route('customer.delete', $employee->user_id ) }}" method="POST" style="display:inline;" id="deleteForm{{ $employee->user_id  }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-button" onclick="showDeleteModal(event, 'deleteForm{{ $employee->user_id  }}')">
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
    function deleteConfirm(userId) {
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Hành động này không thể hoàn tác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            preConfirm: () => {
                document.getElementById(`delete-form-${userId}`).submit();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Đã xóa!', 'Tài khoản đã được xóa.', 'success');
            }
        });
    }

    function updateFormAction() {
        var employeeId = document.getElementById('employee_id').value;
        var formAction = '{{ route("employee.update", ":id") }}'.replace(':id', employeeId);
        console.log(formAction)
        document.getElementById('editEmployeeForm').action = formAction; // Cập nhật action của form
    }

    function editEmployee(employeeId) {
        // Gửi yêu cầu AJAX đến server để lấy thông tin nhân viên
        fetch(`{{ url('admin/employee') }}/${employeeId}`)
            .then(response => response.json())
            .then(data => {
                console.log(data.data)
                if (data.status === 'success') {
                    const employee = data.data;

                    // Điền thông tin vào các trường trong modal
                    document.getElementById('full_name').value = employee.full_name;
                    document.getElementById('username').value = employee.user.username;
                    document.getElementById('email').value = employee.email;
                    document.getElementById('date_of_birth').value = employee.date_of_birth.split('T')[0];
                    document.getElementById('gender').value = employee.gender;
                    document.getElementById('address').value = employee.address;
                    document.getElementById('phone').value = employee.phone;
                    document.getElementById('role_name').value = employee.user.role.role_id;
                    document.getElementById('status').value = employee.user.status;
                    document.getElementById('employee_id').value = employee.employee_id;

                    // Hiển thị ảnh đại diện hiện tại
                    const profileImageUrl = `admin/img/employee/${employee.profile_image}`;
                    document.getElementById('currentProfileImage').src = profileImageUrl;

                    // Hiển thị modal
                    const editModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
                    editModal.show();
                } else {
                    Swal.fire('Lỗi', 'Không tìm thấy thông tin nhân viên', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Lỗi', 'Đã xảy ra lỗi khi lấy thông tin nhân viên', 'error');
            });
    }
</script>
<script>
    const editEmployeeModal = document.getElementById('editEmployeeModal');
    editEmployeeModal.addEventListener('show.bs.modal', () => {
        updateFormAction();
    });
</script>