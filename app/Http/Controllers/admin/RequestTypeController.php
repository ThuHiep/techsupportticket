<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\RequestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RequestTypeController extends Controller{

    public function index(Request $request)
    {
        $template = 'admin.requesttype.index';
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $search = trim($request->input('search'));
        $searchPerformed = $request->filled('search');

        if ($searchPerformed) {
            $query = Requesttype::query();

            if ($search !== '') {
                $query->where('request_type_name', 'LIKE', "%{$search}%");
            }

            // Phân trang với 5 mục mỗi trang
            $requesttypes = $query->paginate(5)->appends($request->all());
            $count = $requesttypes->total();
        } else {
            // Không có tìm kiếm nào được thực hiện hoặc ô tìm kiếm trống, hiển thị tất cả loại yêu cầu
            $requesttypes = Requesttype::paginate(5)->appends($request->all());
            $count = $requesttypes->total();
        }
            $data = RequestController::getUnreadRequests();

            // Lấy danh sách request và số lượng request chưa đọc
            $unreadRequests = $data['unreadRequests'];
            $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact('template','logged_user', 'requesttypes', 'count', 'searchPerformed', 'search','unreadRequests',
            'unreadRequestCount'));
    }

    // Hiển thị form tạo mới request type
    public function create()
    {
        $template = 'admin.requesttype.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $nextId = (string)Str::uuid();
        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'nextId',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    // Lưu request type mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'request_type_name' => 'required|string|max:255',
        ]);

        // Tạo mới request type
        $requestType = new RequestType();
        $requestType->request_type_id = $request->input('request_type_id'); // Gán ID
        $requestType->request_type_name = $request->input('request_type_name');
        $requestType->save(); // Lưu vào cơ sở dữ liệu

        return redirect()->route('requesttype.index')->with('success', 'Loại yêu cầu được thêm thành công.');
    }
    //Chỉnh sửa loại yêu cầu
    public function edit($request_type_id)
    {
        $template = 'admin.requesttype.edit';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $requestType = RequestType::findOrFail($request_type_id); // Đảm bảo bạn đang gọi đúng model

        $data = RequestController::getUnreadRequests();

        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'requestType',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    // Cập nhật loại yêu cầu
    public function update(Request $request, $request_type_id)
    {
        $requestType = RequestType::findOrFail($request_type_id); // Sửa từ Department thành RequestType

        $requestType->update([
            'request_type_name' => $request->input('request_type_name'),
        ]);

        return redirect()->route('requesttype.index')
            ->with('success', 'Thông tin loại yêu cầu đã được cập nhật!');
    }

    // Xóa loại yêu cầu
    public function destroy($request_type_id)
    {
        $requesttype = Requesttype::findOrFail($request_type_id);
        $requesttype->delete();

        return redirect()->route('requesttype.index')
            ->with('success', 'Loại yêu cầu đã được xóa!');
    }
}
