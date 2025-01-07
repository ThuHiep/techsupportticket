<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateDepartmentTable extends Migration
{
    public function up()
    {
        Schema::create('department', function (Blueprint $table) {
            $table->string('department_id', 40)->primary();
            $table->string('department_name', 255);
            $table->enum('status', ['active', 'inactive'])->nulable()->default(null);
        });
    }

    public function down()
    {
        Schema::dropIfExists('department');
    }
}
