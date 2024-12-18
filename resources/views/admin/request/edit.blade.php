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
</style>
<body>
    <div class="container">
        <h1>Chỉnh sửa yêu cầu hỗ trợ kỹ thuật</h1>
        <div class="form-container">
            <form action="{{ route('request.update', $requestData->request_id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-columns">
                    <!-- Cột trái -->
                    <div class="form-column-left">
                        <!-- Hàng 1: Mã yêu cầu + Khách hàng + Trạng thái -->
                        <div class="row_left">
                            <div class="form-group">
                                <label for="request_id">Mã yêu cầu</label>
                                <input type="text" id="request_id" name="request_id" value="{{ $requestData->request_id }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="customer_id">Khách hàng</label>
                                <select id="customer_id" name="customer_id" required>
                                    <option value="">--Chọn khách hàng--</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->customer_id }}" {{ (old('customer_id', $requestData->customer_id) == $customer->customer_id) ? 'selected' : '' }}>
                                            {{ $customer->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select id="status" name="status" required>
                                    <option value="">--Chọn trạng thái--</option>
                                    <option value="Chưa xử lý" {{ (old('status', $requestData->status) == 'Chưa xử lý') ? 'selected' : '' }}>Chưa xử lý</option>
                                    <option value="Đang xử lý" {{ (old('status', $requestData->status) == 'Đang xử lý') ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="Hoàn thành" {{ (old('status', $requestData->status) == 'Hoàn thành') ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="Đã hủy" {{ (old('status', $requestData->status) == 'Đã hủy') ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                        </div>

                        <!-- Hàng 2: Phòng ban + Loại yêu cầu -->
                        <div class="row_left">
                            <div class="form-group">
                                <label for="department_id">Phòng ban</label>
                                <select id="department_id" name="department_id" required>
                                    <option value="">--Chọn phòng ban--</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->department_id }}" {{ (old('department_id', $requestData->department_id) == $department->department_id) ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="request_type_id">Loại yêu cầu</label>
                                <select id="request_type_id" name="request_type_id" required>
                                    <option value="">--Chọn loại yêu cầu--</option>
                                    @foreach($requestTypes as $type)
                                        <option value="{{ $type->request_type_id }}" {{ (old('request_type_id', $requestData->request_type_id) == $type->request_type_id) ? 'selected' : '' }}>
                                            {{ $type->request_type_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Hàng 3: Ngày nhận + Ngày hoàn thành -->
                        <div class="row_left">
                            <!-- Nhóm "Ngày nhận" và "Ngày hoàn thành" -->
                            <div class="form-group-row date-group">
                                <div class="form-group">
                                    <label for="create_at">Ngày tạo</label>
                                    <input type="date" id="create_at" name="create_at" value="{{ old('create_at', $requestData->create_at ? $requestData->create_at->format('Y-m-d') : '') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="resolved_at">Ngày hoàn thành</label>
                                    <input type="date" id="resolved_at" name="resolved_at" value="{{ old('resolved_at', $requestData->resolved_at ? $requestData->resolved_at->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Cột phải -->
                    <div class="form-column-right">
                        {{-- Tiêu đề --}}
                        <div class="form-group">
                            <label for="subject">Tiêu đề</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject', $requestData->subject) }}" required>
                            @error('subject')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Mô tả --}}
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea id="description" name="description" rows="14" required>{{ old('description', $requestData->description) }}</textarea>
                            @error('description')
                            <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Nhóm nút Submit và Cancel --}}
                <div class="button-group">
                    <button type="submit" class="submit-button">Cập nhật Yêu cầu</button>
                    <a href="{{ route('request.index') }}" class="cancel-btn">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</body>


