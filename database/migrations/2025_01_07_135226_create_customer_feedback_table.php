<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateCustomerFeedbackTable extends Migration
{
    public function up()
    {
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_id', 40);
            $table->text('message');
            $table->timestamp('created_at'); // Sử dụng created_at
            $table->tinyInteger('is_real')->default(0); // Thêm trường is_real với giá trị mặc định là 0

            $table->foreign('request_id')->references('request_id')->on('request')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_feedback');
    }
}
