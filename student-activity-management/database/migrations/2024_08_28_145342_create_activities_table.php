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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên hoạt động
            $table->text('description')->nullable(); // Mô tả hoạt động
            $table->date('date'); // Ngày tổ chức
            $table->string('location'); // Địa điểm tổ chức
            $table->date('registration_start')->nullable(); // Ngày bắt đầu đăng ký
            $table->date('registration_end')->nullable(); // Ngày kết thúc đăng ký
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
