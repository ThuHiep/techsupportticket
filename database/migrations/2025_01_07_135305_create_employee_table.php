<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->string('employee_id', 40)->primary();
            $table->string('user_id', 40);
            $table->string('full_name', 255);
            $table->date('date_of_birth');
            $table->string('phone', 20);
            $table->enum('gender', ['Nam', 'Nữ']);
            $table->string('address', 255);
            $table->string('profile_image', 255);
            $table->string('email', 255);
            $table->timestamp('create_at')->nullable(); // Sử dụng create_at thay vì created_at
            $table->timestamp('update_at')->nullable(); // Sử dụng update_at thay vì updated_at

            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee');
    }
}
