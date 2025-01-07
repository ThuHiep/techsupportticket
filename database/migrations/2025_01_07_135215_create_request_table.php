<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTable extends Migration
{
    public function up()
    {
        Schema::create('request', function (Blueprint $table) {
            $table->string('request_id', 40)->primary();
            $table->string('request_type_id', 40);
            $table->string('customer_id', 40);
            $table->string('department_id', 40)->nullable()->default(null);
            $table->string('subject', 255);
            $table->text('description');
            $table->timestamp('resolved_at')->nullable()->default(null); // Sử dụng create_at thay vì created_at
            $table->enum('status', ['Chưa xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy']);
            $table->timestamp('create_at')->nullable(); // Sử dụng create_at thay vì created_at
            $table->timestamp('update_at')->nullable(); // Sử dụng update_at thay vì updated_at

            $table->foreign('request_type_id')->references('request_type_id')->on('request_type')->onDelete('cascade');
            $table->foreign('customer_id')->references('customer_id')->on('customer')->onDelete('cascade');
            $table->foreign('department_id')->references('department_id')->on('department')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('request');
    }

}
