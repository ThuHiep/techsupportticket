<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentTable extends Migration
{
    public function up()
    {
        Schema::create('attachment', function (Blueprint $table) {
            $table->string('attachment_id', 40)->primary();
            $table->string('request_id', 40);
            $table->string('filename', 255)->nullable()->default(null);
            $table->string('fileimg', 255)->nullable()->default(null);
            $table->text('file_path');
            $table->int('file_size');
            $table->string('file_type',50);
            $table->timestamp('created_at')->nullable(); // Sử dụng create_at thay vì created_at

            $table->foreign('request_id')->references('request_id')->on('request')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attachment');
    }
}
