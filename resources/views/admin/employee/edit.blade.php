<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa thông tin nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="employee_id" name="employee_id">
            <div class="modal-body">
                <form id="editEmployeeForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Hình ảnh đại diện</label>
                                <div class="d-flex align-items-center">
                                    <img id="currentProfileImage" src="" alt="Hình đại diện" class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;">

                                    <input type="file" id="profile_image" name="profile_image" class="form-control w-50">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Tên người dùng</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên tài khoản</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <!-- Cột bên phải -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Giới tính</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="role_name" class="form-label">Vai trò</label>
                                <select class="form-select" id="role_name" name="role_name" required>
                                    <option value="1">Admin</option>
                                    <option value="2">Nhân viên</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Ngừng hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>