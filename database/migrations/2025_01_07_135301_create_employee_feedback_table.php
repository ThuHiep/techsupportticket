<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeFeedbackTable extends Migration
{
    public function up()
    {
        Schema::create('employee_feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_id', 40);
            $table->string('employee_id', 40);
            $table->text('message');
            $table->timestamp('created_at'); // Sử dụng created_at
            $table->tinyInteger('is_real')->nulable()->default(0);


            $table->foreign('request_id')->references('request_id')->on('request')->onDelete('cascade');
            $table->foreign('employee_id')->references('employee_id')->on('employee')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_feedback');
    }
}
