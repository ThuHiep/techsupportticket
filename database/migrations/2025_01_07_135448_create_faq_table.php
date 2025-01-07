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
        Schema::create('faq', function (Blueprint $table) {
            $table->string('faq_id', 40)->primary();
            $table->string('email', 255);
            $table->string('employee_id', 40)->nullable()->default(null);
            $table->text('question');
            $table->text('answer')->nullable()->default(null);
            $table->timestamp('create_at')->nullable(); // Sử dụng create_at thay vì created_at
            $table->timestamp('update_at')->nullable(); // Sử dụng update_at thay vì updated_at
            $table->enum('status', ['Đã phản hồi', 'Chưa phản hồi']);

            $table->foreign('employee_id')->references('employee_id')->on('employee')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq');
    }
};
