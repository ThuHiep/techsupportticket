<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class ArticlesController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.articles.index';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        $search = $request->input('search');
        $date = $request->input('date');

        $articles = Article::when($search, function ($query) use ($search) {
            return $query->where('title', 'LIKE', "%$search%");
        })
            ->when($date, function ($query) use ($date) {
                return $query->whereDate('create_at', $date);
            })
            ->paginate(5);


        $totalResults = $articles->total();

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'articles',
            'search',
            'date',
            'totalResults',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    public function create()
    {
        $template = 'admin.articles.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        $nextId = (string) Str::uuid();

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

    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'article_id' => 'required|unique:articles,article_id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra file ảnh
        ]);


        // Lấy thông tin người dùng hiện tại
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        // Xử lý lưu ảnh
        if ($request->hasFile('images')) {
            $file = $request->file('images'); // Lấy file từ request
            $fileName = 'articles_' . time() . '.' . $file->getClientOriginalExtension(); // Đặt tên file
            $destinationPath = public_path('admin/img/articles'); // Đường dẫn lưu file

            // Di chuyển file vào thư mục đích
            $file->move($destinationPath, $fileName);

            // Chỉ lưu tên file vào cơ sở dữ liệu
            $imagePath = $fileName;
        } else {
            $imagePath = null; // Nếu không có ảnh
        }

        // Tạo bài viết mới
        $article = new Article();
        $article->article_id = $request->input('article_id');
        $article->title = $request->input('title');
        $article->content = nl2br(e($request->input('content'))); // Chuyển đổi ký tự xuống dòng thành <br> trước khi lưu

        $article->images = $imagePath; // Lưu đường dẫn ảnh vào cột 'images'
        $article->employee_id = $logged_user->employee_id;
        $article->create_at = now();
        $article->save();

        return redirect()->route('articles.index')->with('success', 'Bài viết đã được thêm thành công!');
    }


    public function edit($article_id)
    {
        $template = 'admin.articles.edit';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $article = Article::findOrFail($article_id);

        $data = RequestController::getUnreadRequests();

        // Lấy danh sách request và số lượng request chưa đọc
        $unreadRequests = $data['unreadRequests'];
        $unreadRequestCount = $data['unreadRequestCount'];

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'article',
            'unreadRequests',
            'unreadRequestCount'
        ));
    }

    public function update(Request $request, $article_id)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra file ảnh
        ]);

        // Lấy bài viết cần cập nhật
        $article = Article::findOrFail($article_id);
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        // Xóa ảnh cũ nếu có và người dùng upload ảnh mới
        if ($request->hasFile('images')) {
            if ($article->images && file_exists(public_path('admin/img/articles/' . $article->images))) {
                unlink(public_path('admin/img/articles/' . $article->images)); // Xóa ảnh cũ
            }

            // Lưu ảnh mới
            $file = $request->file('images');
            $fileName = 'update_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('admin/img/articles'); // Thư mục lưu ảnh
            $file->move($destinationPath, $fileName); // Di chuyển file vào thư mục

            $article->images = $fileName; // Lưu tên ảnh mới vào DB
        }

        // Cập nhật thông tin bài viết
        $article->title = $request->input('title');
        $content = $request->input('content');
        $content = strip_tags($content, '<br>'); // Cho phép thẻ <br> nhưng loại bỏ các thẻ khác
        $article->content = nl2br($content);
        $article->employee_id = $logged_user->employee_id;
        $article->update_at = now();
        $article->save();

        return redirect()->route('articles.index')->with('success', 'Bài viết đã được cập nhật thành công!');
    }


    public function destroy($article_id)
    {
        $article = Article::findOrFail($article_id);

        // Kiểm tra đường dẫn file
        $filePath = public_path('admin/img/articles/' . $article->images);

        // Kiểm tra và xóa ảnh nếu có
        if ($article->images && file_exists($filePath)) {
            unlink($filePath);
        }

        // Xóa bài viết
        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Bài viết và ảnh đã được xóa!');
    }
}
