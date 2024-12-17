<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    // Lấy tất cả các bài viết
    public function index()
    {
        $articles = Article::all(); // Lấy tất cả bài viết
        return view('articles.index', compact('articles'));
    }

    // Tạo một bài viết mới
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'required|string',
        ]);

        // Tạo một bài viết mới
        $article = new Article();
        $article->content = $request->input('content'); // Gán giá trị cho content
        $article->title = $request->input('title'); // Gán giá trị cho title
        $article->article_id = $request->input('article_id'); // Nếu cần gán article_id
        // Gán giá trị cho các trường khác nếu cần

        $article->save(); // Lưu bài viết vào cơ sở dữ liệu

        return response()->json($article, 201); // Trả về bài viết vừa tạo
    }

    // Cập nhật bài viết
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu
        $request->validate([
            'content' => 'required|string',
        ]);

        $article = Article::findOrFail($id);

        // Cập nhật nội dung
        $article->content = $request->input('content'); // Gán giá trị từ request

        // Lưu thay đổi vào cơ sở dữ liệu
        $article->save();

        // Trả về bài viết đã cập nhật
        return response()->json($article);
    }

    // Xóa bài viết
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return response()->json(null, 204);
    }
}
