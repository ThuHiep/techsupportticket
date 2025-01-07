<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->string('articles_id', 40)->primary();
            $table->string('employee_id', 40);
            $table->string('title', 255);
            $table->text('content');
            $table->string('images', 500)->nullable()->default(null);
            $table->timestamp('create_at')->nullable(); // Sử dụng create_at thay vì created_at
            $table->timestamp('update_at')->nullable(); // Sử dụng update_at thay vì updated_at

            $table->foreign('employee_id')->references('employee_id')->on('employee')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
