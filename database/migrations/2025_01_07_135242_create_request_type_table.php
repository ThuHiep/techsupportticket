<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTypeTable extends Migration
{
    public function up()
    {
        Schema::create('request_type', function (Blueprint $table) {
            $table->string('request_type_id', 40)->primary();
            $table->string('request_type_name', 255);
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_type');
    }
}
