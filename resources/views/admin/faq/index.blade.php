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
                <form action="{{ route('faq.index') }}" method="GET" style="display: flex; width: 70%; gap: 15px; align-items: center;">
                    <!-- Input tìm kiếm -->
                    <div style="position: relative; flex: 2;">
                        <input type="text" name="search" placeholder="Nhập mã hoặc nội dung câu hỏi" value="{{ request()->query('search') }}">
                        @if(request()->query('search'))
                        <!-- Biểu tượng X -->
                        <a href="{{ route('faq.index') }}"
                            id="clearButton"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); text-decoration: none; color: #D5D5D5; font-size: 18px; cursor: pointer;">
                            ✖
                        </a>
                        @endif
                    </div>
                    <input type="date" id="searchDate" name="date" placeholder="Tìm kiếm theo ngày" value="{{ request('date') }}" />
                    <!-- Nút tìm kiếm -->
                    <button type="submit">Tìm kiếm</button>
                </form>
            </div>

        </div>

        @if ($isSearchPerformed)
        {{-- Trường hợp lọc bài viết chưa phản hồi vào ngày hôm nay --}}
        @if ($isTodaySearch)
        @if ($totalResults > 0)
        <div  id="search-notification" class="alert alert-success" style="text-align: center; color: green; margin-top: 10px;">
            Tìm thấy {{ $totalResults }} câu hỏi chưa phản hồi vào ngày hôm nay.
        </div>
        @else
        <div  id="search-notification" class="alert alert-danger" style="text-align: center; color: red; margin-top: 10px;">
            Không tìm thấy câu hỏi chưa phản hồi vào ngày hôm nay.
        </div>
        @endif
        
        @else
        {{-- Trường hợp tìm theo mã FAQ --}}
        @if ($isSearchById)
        @if ($totalResults > 0)
        <div  id="search-notification" class="alert alert-success" style="text-align: center; color: green; margin-top: 10px;">
            Tìm thấy câu hỏi với mã: "{{ $search }}"
        </div>
        @else
        <div  id="search-notification" class="alert alert-danger" style="text-align: center; color: red; margin-top: 10px;">
            Không tìm thấy câu hỏi với mã: "{{ $search }}"
        </div>
        @endif

        {{-- Trường hợp tìm theo từ khóa và ngày --}}
        @elseif ($isSearchWithDate)
        @if ($totalResults > 0)
        <div  id="search-notification" class="alert alert-success" style="text-align: center; color: green; margin-top: 10px;">
            Tìm thấy {{ $totalResults }} câu hỏi chưa phản hồi chứa từ khóa: "{{ $search }}" vào ngày "{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}"
        </div>
        @else
        <div  id="search-notification" class="alert alert-danger" style="text-align: center; color: red; margin-top: 10px;">
            Không tìm thấy câu hỏi chưa phản hồi chứa từ khóa: "{{ $search }}" vào ngày "{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}"
        </div>
        @endif

        {{-- Trường hợp chỉ tìm theo ngày --}}
        @elseif ($date)
        @if ($totalResults > 0)
        <div  id="search-notification" class="alert alert-success" style="text-align: center; color: green; margin-top: 10px;">
            Tìm thấy {{ $totalResults }} câu hỏi chưa phản hồi vào ngày "{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}"
        </div>
        @else
        <div  id="search-notification" class="alert alert-danger" style="text-align: center; color: red; margin-top: 10px;">
            Không tìm thấy câu hỏi chưa phản hồi vào ngày "{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}"
        </div>
        @endif

        {{-- Trường hợp chỉ tìm theo từ khóa --}}
        @elseif ($search)
        @if ($totalResults > 0)
        <div  id="search-notification" class="alert alert-success" style="text-align: center; color: green; margin-top: 10px;">
            Tìm thấy {{ $totalResults }} câu hỏi chưa phản hồi chứa từ khóa: "{{ $search }}"
        </div>
        @else
        <div  id="search-notification" class="alert alert-danger" style="text-align: center; color: red; margin-top: 10px;">
            Không tìm thấy câu hỏi chưa phản hồi chứa từ khóa: "{{ $search }}"
        </div>
        @endif
        @endif
        @endif
        @endif

        <!-- Bảng danh sách câu hỏi -->
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
                            <form action="{{ route('faq.feedback', $faq->faq_id) }}" style="display:inline;">
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

        <!-- Phân trang -->
        <div class="pagination">
            {{ $faqs->links('pagination::bootstrap-4') }}
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tự động ẩn thông báo tìm kiếm sau 5 giây
        setTimeout(function() {
            var searchNotification = document.getElementById('search-notification');
            if (searchNotification) {
                searchNotification.style.transition = 'opacity 0.5s ease-out';
                searchNotification.style.opacity = '0';
                setTimeout(() => searchNotification.style.display = 'none', 500); // Ẩn hoàn toàn sau hiệu ứng mờ dần
            }
        }, 3000);

    </script>
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