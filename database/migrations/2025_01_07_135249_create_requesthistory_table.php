<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('requesthistory', function (Blueprint $table) {
            $table->string('history_id',40)->primary();
            $table->string('request_id', 40);
            $table->string('changed_by',40)->nullable()->default(null);
            $table->string('department_id',40)->nullable()->default(null);
            $table->enum('old_status', ['Chưa xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy'])->nullable()->default(null);
            $table->enum('new_status', ['Chưa xử lý', 'Đang xử lý', 'Hoàn thành', 'Đã hủy']);
            $table->text('note')->nullable()->default(null);
            $table->datetime('changed_at')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('request_id')->on('request')->onDelete('cascade');
            $table->foreign('changed_by')->references('changed_by')->on('employee')->onDelete('cascade');
            $table->foreign('department_id')->references('department_id')->on('department')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('requesthistory');
    }
}
