<link rel="stylesheet" href="{{ asset('admin/css/request/edit.css') }}">

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
    .history-table th, .history-table td {
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
                            <label for="customer_id">Khách hàng <span class="required">*</span></label>
                            <select id="customer_id" name="customer_id" required>
                                <option value="">--Chọn khách hàng--</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->customer_id }}" {{ (old('customer_id', $supportRequest->customer_id) == $customer->customer_id) ? 'selected' : '' }}>
                                        {{ $customer->full_name }}
                                    </option>
                                @endforeach
                            </select>
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

                    <!-- Hàng 3: Ngày tạo + Ngày hoàn thành -->
                    <div class="row_left">
                        <!-- Nhóm "Ngày tạo" và "Ngày hoàn thành" -->
                        <div class="form-group-row date-group">
                            <div class="form-group">
                                <label for="create_at">Ngày tạo <span class="required">*</span></label>
                                <input type="date" id="create_at" name="create_at" value="{{ old('create_at', $supportRequest->create_at ? $supportRequest->create_at->format('Y-m-d') : '') }}" readonly required>
                                @error('create_at')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="resolved_at">Ngày hoàn thành</label>
                                <input type="date" id="resolved_at" name="resolved_at" value="{{ old('resolved_at', $supportRequest->resolved_at ? $supportRequest->resolved_at->format('Y-m-d') : '') }}">
                                @error('resolved_at')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
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

    <!-- Phần Phản hồi -->
    <div class="reply-container" style="display: none;">
        <h3>Phản hồi</h3>
        <form id="replyForm" method="POST" action="{{ route('request.reply', $supportRequest->request_id) }}">
            @csrf
            
                @include('admin.request.reply-cus')
            {{-- /////////////////////////// --}}
            {{-- <div class="form-group">
                <label for="reply_content">Nội dung phản hồi:</label>
                <textarea id="reply_content" name="reply_content" rows="4" required></textarea>
                @error('reply_content')
                <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="submit-button">Gửi Phản Hồi</button>
            <button type="button" class="cancel-btn" onclick="hideReplyForm()">Hủy</button> --}}
        </form>
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
                    <th>Người thay đổi</th>
                    <th>Ghi chú</th>
                </tr>
                </thead>
                <tbody>
                <!-- Thay vì lặp $supportRequest->history, ta lặp $sortedHistory -->
                @foreach($sortedHistory as $history)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($history->changed_at)->format('d/m/Y H:i') }}</td>
                        <td>{{ $history->new_status }}</td>
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
        if(newFileNameElement) {
            newFileNameElement.innerText = fileName ? `File mới: ${fileName}` : '';
        }

        const fileLabel = event.target.nextElementSibling;
        if(fileLabel && fileLabel.classList.contains('custom-file-label')) {
            fileLabel.innerText = fileName ? fileName : 'Chọn file';
        }
    });

    function showReplyForm() {
        document.querySelector('.reply-container').style.display = 'block';
        document.querySelector('.history-container').style.marginTop = '20px'; // Đẩy lịch sử yêu cầu xuống
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
            if(departmentSelect.value && initialStatus === 'Chưa xử lý') {
                statusSelect.value = 'Đang xử lý';
                const chuaXuLyOption = statusSelect.querySelector('option[value="Chưa xử lý"]');
                if(chuaXuLyOption) {
                    chuaXuLyOption.remove();
                }
            } else if (!departmentSelect.value && initialStatus !== 'Chưa xử lý') {
                statusSelect.value = 'Chưa xử lý';
                if(!statusSelect.querySelector('option[value="Chưa xử lý"]')) {
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
    $(document).ready(function(){

        $('.summernote').summernote();

    });

</script>
</body>
