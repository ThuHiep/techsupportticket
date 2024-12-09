<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .modal.show {
        display: block !important;
        opacity: 1 !important;
    }
</style>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header align-items-center justify-content-between">
            <h1 class="card-title">Danh sách nhân viên</h1>
            <div class="d-flex align-items-center justify-content-between">
                <div class="card-tools">
                    <button class="btn btn-outline-danger mx-2 text-left" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">Thêm mới</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Mã tài khoản</th>
                        <th class="text-center">Tên người dùng</th>
                        <th class="text-center">Tên tài khoản</th>
                        <th class="text-center">Ảnh đại diện</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Vai trò</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center" style="width:160px">Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr class="text-center">
                        <td class="align-middle">{{ $employee->user_id }}</td>
                        <td class="align-middle">{{ $employee->full_name }}</td>
                        <td class="align-middle">{{ $employee->username }}</td>
                        <td class="align-middle">
                            <img src="{{ asset('admin/img/employee/' . $employee->profile_image) }}" style="width: 70px;width: 70px;object-fit: cover;" alt="Hình ảnh khách hàng" class="employee-image">
                        </td>
                        <td class="align-middle">{{ $employee->email }}</td>
                        <td class="align-middle">{{ $employee->role_name }}</td>
                        <td class="align-middle">{{ $employee->status }}</td>
                        <td class="align-middle">
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary btn-sm mx-1" onclick="editEmployee('{{ $employee->employee_id }}')">Chỉnh sửa</button>

                                <button class="btn btn-danger btn-sm mx-1" onclick="deleteConfirm('{{ $employee->user_id }}')">Xóa</button>

                                <form id="delete-form-{{ $employee->user_id }}" method="POST" action="{{ route('employee.delete', $employee->user_id) }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="d-flex justify-content-center">
            {{ $employees->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <!-- /.card -->
    @include('admin.employee.edit')
    @include('admin.employee.create')

</section>
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