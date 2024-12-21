<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{
    public function index(Request $request)
    {
        $template = 'admin.articles.index';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        $search = $request->input('search');
        $date = $request->input('date');

        $articles = Article::when($search, function ($query) use ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('article_id', $search)
                    ->orWhere('title', 'LIKE', "%$search%");
            });
        })
        ->when($date, function ($query) use ($date) {
            return $query->whereDate('create_at', $date);
        })
        ->paginate(5);

        $totalResults = $articles->total();

        return view('admin.dashboard.layout', compact(
            'template',
            'logged_user',
            'articles',
            'search',
            'date',
            'totalResults'
        ));
    }

    public function create()
    {
        $template = 'admin.articles.create';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        do {
            $randomNumber = mt_rand(1, 9999);
            $nextId = 'ART' . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
            $exists = Article::where('article_id', $nextId)->exists();
        } while ($exists);

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'nextId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|unique:articles,article_id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $article = new Article();
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        $article->article_id = $request->input('article_id');
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->employee_id = $logged_user->employee_id;
        $article->create_at = now();
        $article->save();

        return redirect()->route('article.index')->with('success', 'Bài viết đã được thêm thành công!');
    }

    public function edit($article_id)
    {
        $template = 'admin.articles.edit';
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();
        $article = Article::findOrFail($article_id);

        return view('admin.dashboard.layout', compact('template', 'logged_user', 'article'));
    }

    public function update(Request $request, $article_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $article = Article::findOrFail($article_id);
        $logged_user = Employee::with('user')->where('user_id', '=', Auth::user()->user_id)->first();

        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->employee_id = $logged_user->employee_id;
        $article->update_at = now();
        $article->save();

        return redirect()->route('article.index')->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    public function destroy($article_id)
    {
        $article = Article::findOrFail($article_id);
        $article->delete();

        return redirect()->route('article.index')->with('success', 'Bài viết đã được xóa!');
    }
}
