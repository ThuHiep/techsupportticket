<link rel="stylesheet" href="{{ asset('admin/css/request/edit.css') }}">

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

    .required {
        color: red;
        font-size: 14px;
    }

    /* Styles cho lịch sử */
    .history-container {
        margin-top: 40px;
    }

    .history-container h2 {
        margin-bottom: 20px;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
    }

    .history-table th,
    .history-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .history-table th {
        background-color: #f2f2f2;
    }

    .error {
        color: red;
        font-size: 13px;
    }

    .feedback-container {
        max-width: 100%;
        margin: 20px auto;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    .feedback-item {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
        /* Đặt relative để định vị thời gian */
    }

    .feedback-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        position: relative;
        /* Đặt relative để căn thời gian */
    }

    .feedback-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
    }

    .feedback-user-info {
        display: flex;
        flex-direction: column;
    }

    .feedback-name {
        font-size: 16px;
        font-weight: bold;
        margin: 0;
        color: #333;
    }

    .feedback-type {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
        font-style: italic;
    }

    .feedback-time {
        position: absolute;
        top: 0;
        right: 15px;
        font-size: 12px;
        color: #888;
        margin: 0;
        white-space: nowrap;
        /* Đảm bảo thời gian không xuống dòng */
    }

    .feedback-message {
        font-size: 14px;
        color: #444;
        margin: 0;
        line-height: 1.5;
    }
</style>

<body>
    <div class="container">
        <h1>Chỉnh sửa yêu cầu hỗ trợ kỹ thuật</h1>
        <div class="form-container">
            <form action="{{ route('request.update', $supportRequest->request_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-columns">
                    <!-- Cột trái -->
                    <div class="form-column-left">
                        <!-- Hàng 1: Mã yêu cầu + Khách hàng + Trạng thái -->
                        <div class="row_left">

                            <div class="form-group">
                                <label for="customer_name">Khách hàng <span class="required">*</span></label>
                                <!-- Hiển thị tên khách hàng -->
                                <input type="text" id="customer_name" name="customer_name" value="{{ $supportRequest->customer->full_name }}" readonly>
                                <!-- Trường ẩn để gửi customer_id -->
                                <input type="hidden" id="customer_id" name="customer_id" value="{{ $supportRequest->customer_id }}">
                                @error('customer_id')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">Trạng thái <span class="required">*</span></label>
                                <select id="status" name="status" required>
                                    <option value="">--Chọn trạng thái--</option>
                                    <option value="Chưa xử lý" {{ (old('status', $supportRequest->status) == 'Chưa xử lý') ? 'selected' : '' }}>Chưa xử lý</option>
                                    <option value="Đang xử lý" {{ (old('status', $supportRequest->status) == 'Đang xử lý') ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="Hoàn thành" {{ (old('status', $supportRequest->status) == 'Hoàn thành') ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="Đã hủy" {{ (old('status', $supportRequest->status) == 'Đã hủy') ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                @error('status')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Hàng 2: Phòng ban + Loại yêu cầu -->
                        <div class="row_left">
                            <div class="form-group">
                                <label for="department_id">Phòng ban <span class="required">*</span></label>
                                <select id="department_id" name="department_id" required>
                                    <option value="">--Chọn phòng ban--</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}" {{ (old('department_id', $supportRequest->department_id) == $department->department_id) ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="request_type_id">Loại yêu cầu <span class="required">*</span></label>
                                <select id="request_type_id" name="request_type_id" required>
                                    <option value="">--Chọn loại yêu cầu--</option>
                                    @foreach($requestTypes as $type)
                                    <option value="{{ $type->request_type_id }}" {{ (old('request_type_id', $supportRequest->request_type_id) == $type->request_type_id) ? 'selected' : '' }}>
                                        {{ $type->request_type_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('request_type_id')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



        <div class="form-group attachments">
            <label for="attachments">{{ $supportRequest->attachment ? 'Cập nhật File đính kèm:' : 'Thêm File Đính Kèm:' }}</label>
            <div class="custom-file">
                <input type="file" name="attachments" class="custom-file-input" id="attachments">
                <label class="custom-file-label" for="attachments">Chọn file</label>
            </div>
            <small class="form-text text-muted">
                Bạn chỉ được tải 1 file. Định dạng: jpg, jpeg, png, pdf, doc, docx, txt. Dung lượng tối đa: 40MB.
            </small>
            @error('attachments')
            <div class="error">{{ $message }}</div>
            @enderror

            @if($supportRequest->attachment)
            <div class="existing-attachment mt-2">
                <div class="alert alert-info">
                    <strong>File hiện tại:</strong> {{ $supportRequest->attachment->filename }}
                    <a href="{{ route('attachments.download', $supportRequest->attachment->attachment_id) }}" class="btn btn-sm btn-primary ml-3">
                        <i class="fas fa-download"></i> Tải Xuống
                    </a>
                </div>
            </div>
            @endif

            <div id="new-file-name" class="mt-2"></div>
        </div>

    </div>


    <!-- Cột phải -->
    <div class="form-column-right">
        {{-- Tiêu đề --}}
        <div class="form-group">
            <label for="subject">Tiêu đề <span class="required">*</span></label>
            <input type="text" id="subject" name="subject" value="{{ old('subject', $supportRequest->subject) }}" required>
            @error('subject')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
        {{-- Mô tả --}}
        <div class="form-group">
            <label for="description">Mô tả <span class="required">*</span></label>
            <textarea id="description" name="description" rows="8" required>{{ old('description', $supportRequest->description) }}</textarea>
            @error('description')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>
        {{-- Nhóm nút Submit và Cancel --}}
        <div class="button-group">
            <button type="submit" class="submit-button">Cập nhật </button>
            <a href="#" class="reply-button" onclick="showReplyForm(); return false;">Phản hồi</a>
            <a href="{{ route('request.index') }}" class="cancel-btn">Hủy</a>
        </div>
    </div>
    </div>
    </form>
    </div>


    <strong>
        <h2>Phản hồi</h2>
    </strong>
    <div class="feedback-container">
        @foreach ($feedbacks as $feedback)
        <div class="feedback-item">
            <div class="feedback-header">
                @if( $feedback->role_id == 1 || $feedback->role_id == 2)
                <img src="{{$feedback->profile_image ? asset('admin/img/employee/' .  $feedback->profile_image) : asset('admin/img/employee/default.png') }}" alt="Hình ảnh nhân viên" class="feedback-avatar">
                @elseif($feedback->role_id == 3)
                <img src="{{ $feedback->profile_image ? asset('admin/img/customer/' . $feedback->profile_image) : asset('admin/img/customer/default.png') }}" alt="Hình ảnh khách hàng" class="feedback-avatar">
                @endif
                <div class="feedback-user-info">
                    <p class="feedback-name">{{ $feedback->full_name }}</p>
                    @if( $feedback->role_id == 1 || $feedback->role_id == 2)
                    <p class="feedback-type">Nhân viên hỗ trợ</p>
                    @elseif($feedback->role_id == 3)
                    <p class="feedback-type">Chủ sở hưu</p>
                    @endif
                </div>
                <p class="feedback-time">
                    {{ \Carbon\Carbon::parse($feedback->created_at)->format('H:i d/m/Y') }}
                </p>
            </div>
            <div class="feedback-message">
                {!! $feedback->message !!}
            </div>
        </div>
        @endforeach
    </div>
    <!-- Phần Phản hồi -->
    <div class="reply-container" style="display: none;">
        @include('admin.request.reply-cus')
    </div>



    <!-- Phần Lịch Sử Yêu Cầu -->
    <div class="history-container">
        <h1>Lịch sử trạng thái yêu cầu</h1>
        @if($supportRequest->history->count() > 0)
        <!-- Thêm đoạn sắp xếp tạm ở đây -->
        @php
        // Sắp xếp theo 'changed_at' giảm dần để bản ghi mới nhất lên đầu
        $sortedHistory = $supportRequest->history->sortByDesc('changed_at');
        @endphp

        <table class="history-table">
            <thead>
            <tr>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Phòng ban tiếp nhận</th> <!-- thêm cột -->
                <th>Người thay đổi</th>
                <th>Ghi chú</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sortedHistory as $history)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($history->changed_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $history->new_status }}</td>

                    <!-- Hiển thị tên phòng ban -->
                    <td>
                        @if($history->department_id)
                            {{ optional($history->department)->department_name ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>

                    <td>
                        @if($history->changed_by)
                            {{ $history->employee->full_name ?? 'N/A' }}
                        @else
                            Hệ thống
                        @endif
                    </td>
                    <td>{{ $history->note }}</td>
                </tr>
            @endforeach
            </tbody>

        </table>
        @else
        <p>Không có lịch sử trạng thái nào để hiển thị.</p>
        @endif
    </div>
    </div>

    <!-- JavaScript Cập Nhật Tên File -->
    <script>
        // Cập nhật tên file khi chọn file
        document.querySelector('.custom-file-input').addEventListener('change', function(event) {
            const fileName = event.target.files[0] ? event.target.files[0].name : 'Chọn file';

            const newFileNameElement = document.getElementById('new-file-name');
            if (newFileNameElement) {
                newFileNameElement.innerText = fileName ? `File mới: ${fileName}` : '';
            }

            const fileLabel = event.target.nextElementSibling;
            if (fileLabel && fileLabel.classList.contains('custom-file-label')) {
                fileLabel.innerText = fileName ? fileName : 'Chọn file';
            }
        });

        function showReplyForm() {
            const replyContainer = document.querySelector('.reply-container');
            const historyContainer = document.querySelector('.history-container');

            if (replyContainer.style.display === 'block') {
                replyContainer.style.display = 'none';
                historyContainer.style.marginTop = '0';
            } else {
                replyContainer.style.display = 'block';
                historyContainer.style.marginTop = '20px';
            }
        }

        function hideReplyForm() {
            document.querySelector('.reply-container').style.display = 'none';
            document.querySelector('.history-container').style.marginTop = '0'; // Khôi phục lại vị trí
        }
    </script>

    <!-- JavaScript SweetAlert và các script khác -->
    <script>
        // SweetAlert và các script khác như trước
        // ...

        setTimeout(function() {
            var searchNotification = document.getElementById('search-notification');
            if (searchNotification) {
                searchNotification.style.transition = 'opacity 0.5s ease-out';
                searchNotification.style.opacity = '0';
                setTimeout(() => searchNotification.style.display = 'none', 500);
            }
        }, 3000);

        function showDeleteModal(event, formId) {
            event.preventDefault();

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
                    document.getElementById(formId).submit();
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

        document.addEventListener('DOMContentLoaded', function() {
            const searchFieldSelect = document.getElementById('search_field');
            const additionalSearchFields = document.querySelectorAll('.additional-search .search-field');

            function updateSearchFields() {
                const selectedField = searchFieldSelect.value;

                additionalSearchFields.forEach(field => {
                    field.style.display = 'none';
                });

                if (selectedField) {
                    const fieldToShow = document.getElementById(`search_${selectedField}`);
                    if (fieldToShow) {
                        fieldToShow.style.display = 'block';
                    }
                }
            }

            updateSearchFields();

            searchFieldSelect.addEventListener('change', updateSearchFields);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('department_id');
            const statusSelect = document.getElementById('status');

            const initialStatus = statusSelect.value;

            function updateStatusOptions() {
                if (departmentSelect.value && initialStatus === 'Chưa xử lý') {
                    statusSelect.value = 'Đang xử lý';
                    const chuaXuLyOption = statusSelect.querySelector('option[value="Chưa xử lý"]');
                    if (chuaXuLyOption) {
                        chuaXuLyOption.remove();
                    }
                } else if (!departmentSelect.value && initialStatus !== 'Chưa xử lý') {
                    statusSelect.value = 'Chưa xử lý';
                    if (!statusSelect.querySelector('option[value="Chưa xử lý"]')) {
                        const option = document.createElement('option');
                        option.value = 'Chưa xử lý';
                        option.text = 'Chưa xử lý';
                        statusSelect.insertBefore(option, statusSelect.firstChild);
                    }
                }
            }

            departmentSelect.addEventListener('change', updateStatusOptions);
        });
    </script>

    <script>
        $(document).ready(function() {

            $('.summernote').summernote();

        });
    </script>
</body>
