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
        Schema::create('customer', function (Blueprint $table) {
            $table->string('customer_id', 40)->primary();
            $table->string('user_id', 40);
            $table->string('full_name', 255);
            $table->date('date_of_birth');
            $table->string('phone', 20);
            $table->enum('gender', ['Nam', 'Nữ']);
            $table->string('address', 255);
            $table->string('profile_image', 255)->nullable()->default(null);
            $table->string('email', 255);
            $table->string('company', 255)->nullable()->default(null);
            $table->integer('tax_id')->default(null);
            $table->timestamp('create_at')->nullable(); // Sử dụng create_at thay vì created_at
            $table->timestamp('update_at')->nullable(); // Sử dụng update_at thay vì updated_at
            $table->string('software', 255)->nullable()->default(null);
            $table->string('website', 255)->nullable()->default(null);
            $table->enum('status', ['active', 'inactive'])->nullable()->default(null);

            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
