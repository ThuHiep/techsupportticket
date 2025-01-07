<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->string('user_id', 40)->primary();
            $table->string('username',100);
            $table->string('password',255);
            $table->string('otp',6)->nullable()->default(null);
            $table->dateTime('otp_expiration_time')->nullable()->default(null);
            $table->tinyInteger('otp_validation')->nullable()->default(0); // Thêm trường is_real với giá trị mặc định là 0
            $table->timestamp('create_at')->nullable(); // Sử dụng create_at thay vì created_at
            $table->timestamp('update_at')->nullable(); // Sử dụng update_at thay vì updated_at
            $table->enum('status', ['active','inactive'])->nullable()->default(null);
            $table->string('role_id', 40); // Thêm trường role_id

            // Định nghĩa khóa ngoại
            $table->foreign('role_id')->references('role_id')->on('role')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
}
