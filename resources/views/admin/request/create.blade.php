<link rel="stylesheet" href="{{ asset('admin/css/request/create.css') }}">

<div class="container">
    <h1>Tạo mới Yêu cầu Hỗ trợ Kỹ thuật</h1>
    <div class="form-container">
        <form action="{{ route('request.store') }}" method="POST">
            @csrf

            {{-- Nhóm các trường Mã yêu cầu và Ngày nhận vào cùng một hàng --}}
            <div class="form-group-row">
                {{-- Mã yêu cầu (readonly) --}}
                <div class="form-group">
                    <label for="request_id">Mã yêu cầu</label>
                    <input type="text" id="request_id" name="request_id" value="{{ $nextId }}" readonly>
                    @error('request_id')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ngày nhận --}}
                <div class="form-group">
                    <label for="received_at">Ngày nhận</label>
                    <input type="date" id="received_at" name="received_at" value="{{ old('received_at', date('Y-m-d')) }}" required>
                    @error('received_at')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Nhóm các trường Khách hàng và Phòng ban vào cùng một hàng --}}
            <div class="form-group-row">
                {{-- Khách hàng --}}
                <div class="form-group">
                    <label for="customer_id">Khách hàng</label>
                    <select id="customer_id" name="customer_id" required>
                        <option value="">--Chọn khách hàng--</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->customer_id }}" {{ old('customer_id') == $customer->customer_id ? 'selected' : '' }}>
                                {{ $customer->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phòng ban --}}
                <div class="form-group">
                    <label for="department_id">Phòng ban</label>
                    <select id="department_id" name="department_id" required>
                        <option value="">--Chọn phòng ban--</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->department_id }}" {{ old('department_id') == $department->department_id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Nhóm các trường Loại yêu cầu và Ưu tiên vào cùng một hàng --}}
            <div class="form-group-row">
                {{-- Loại yêu cầu --}}
                <div class="form-group">
                    <label for="request_type_id">Loại yêu cầu</label>
                    <select id="request_type_id" name="request_type_id" required>
                        <option value="">--Chọn loại yêu cầu--</option>
                        @foreach($requestTypes as $type)
                            <option value="{{ $type->request_type_id }}" {{ old('request_type_id') == $type->request_type_id ? 'selected' : '' }}>
                                {{ $type->request_type_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('request_type_id')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ưu tiên --}}
                <div class="form-group">
                    <label for="priority">Ưu tiên</label>
                    <select id="priority" name="priority" required>
                        <option value="">--Chọn ưu tiên--</option>
                        <option value="Thấp" {{ old('priority') == 'Thấp' ? 'selected' : '' }}>Thấp</option>
                        <option value="Trung bình" {{ old('priority') == 'Trung bình' ? 'selected' : '' }}>Trung bình</option>
                        <option value="Cao" {{ old('priority') == 'Cao' ? 'selected' : '' }}>Cao</option>
                    </select>
                    @error('priority')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Nhóm trường Tiêu đề và Mô tả vào cùng một hàng --}}
            <div class="form-group-row">
                {{-- Tiêu đề --}}
                <div class="form-group">
                    <label for="subject">Tiêu đề</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required>
                    @error('subject')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mô tả --}}
                <div class="form-group">
                    <label for="description">Mô tả</label>
                    <textarea id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Nhóm nút Submit và Cancel --}}
            <div class="button-group">
                <button type="submit" class="submit-button">Lưu Yêu cầu</button>
                <a href="{{ route('request.index') }}" class="cancel-button">Hủy</a>
            </div>
        </form>
    </div>
</div>
