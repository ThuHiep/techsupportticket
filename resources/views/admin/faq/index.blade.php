<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách câu hỏi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/faq/index.css') }}">
    <style>
        body .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }
        body.mini-navbar .container {
            width: calc(98%);
            transition: all 0.3s ease-in-out;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Danh sách câu hỏi</h1>
        <div class="top-bar">
            <a href="{{ route('faq.create') }}" class="add-faq-btn">Thêm mới</a>
            <div class="search-container">
                <form action="{{ route('faq.index') }}" method="GET">
                    
                    <input type="text" name="search" placeholder="Nhập câu hỏi cần tìm" value="{{ request()->query('search') }}">
                    
                    
                    <select name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="Đã phản hồi" {{ request()->query('status') == 'Đã phản hồi' ? 'selected' : '' }}>Đã phản hồi</option>
                        <option value="Chưa phản hồi" {{ request()->query('status') == 'Chưa phản hồi' ? 'selected' : '' }}>Chưa phản hồi</option>
                    </select>
                    
                    <button type="submit">Tìm kiếm</button>
                </form>
            </div>
        </div>

        <div class="search-result-message 
            @if(request()->query('search') || request()->query('status'))
                {{ $totalResults > 0 ? 'success' : 'error' }}
            @endif">
            @if(request()->query('search') || request()->query('status'))
                @if($totalResults > 0)
                    <div class="alert-success" style="text-align: center; color: green; margin-bottom: 15px;">
                        Tìm thấy {{ $totalResults }} câu hỏi
                   
                        @if(request()->query('search'))
                            có từ khóa "{{ request()->query('search') }}"
                        @endif
                        @if(request()->query('status'))
                            với trạng thái "{{ request()->query('status') }}"
                        @endif.
                    </div>
                @else
                    <div class="alert-danger" style="text-align: center; color: red; margin-bottom: 15px;">
                        Không tìm thấy câu hỏi 
                        @if(request()->query('search'))
                            có từ khóa "{{ request()->query('search') }}"
                        @endif
                        @if(request()->query('status'))
                            với trạng thái "{{ request()->query('status') }}"
                        @endif.
                    </div>
                @endif
            @endif
        </div>



        
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã câu hỏi</th>
                    <th>Email</th>
                    <th>Câu hỏi</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($faqs as $index => $faq)
                    <tr>
                        <td>{{ ($faqs->currentPage() - 1) * $faqs->perPage() + $index + 1 }}</td>
                        <td>{{ $faq->faq_id }}</td>
                        <td>{{ $faq->email }}</td>
                        <td>{{ Str::limit($faq->question, 50) }}</td>
                    
                        <td>
                            @if($faq->status == 'Đã phản hồi')
                                <span class="status-dot active"></span> Đã phản hồi
                            @else
                                <span class="status-dot inactive"></span> Chưa phản hồi
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($faq->create_at)->format('d/m/Y H:i') }}</td>

                        <td>
                            <form action="{{ route('faq.edit', $faq->faq_id) }}" style="display:inline;">
                                <button type="submit" class="edit-button">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <form action="{{ route('faq.delete', $faq->faq_id) }}" method="POST" style="display:inline;" id="deleteForm{{ $faq->faq_id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-button" onclick="showDeleteModal(event, 'deleteForm{{ $faq->faq_id }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">
            {{ $faqs->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showDeleteModal(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: 'Bạn có chắc chắn muốn xóa câu hỏi này?',
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
    </script>
</body>
</html>
