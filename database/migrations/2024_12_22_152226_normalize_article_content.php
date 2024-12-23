<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Lấy toàn bộ bài viết từ bảng articles
        $articles = DB::table('articles')->get();

        foreach ($articles as $article) {
            // Chuẩn hóa nội dung: Thay ký tự xuống dòng bằng thẻ <br>
            $normalizedContent = nl2br(e($article->content));
            DB::table('articles')
                ->where('article_id', $article->article_id)
                ->update(['content' => $normalizedContent]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback logic: Khôi phục nội dung về trạng thái ban đầu (nếu cần)
        // Trong trường hợp không cần rollback, có thể để trống hoặc ghi chú lại.
    }
};
