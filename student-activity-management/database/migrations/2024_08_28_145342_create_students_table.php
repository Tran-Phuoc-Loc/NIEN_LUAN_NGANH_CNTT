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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique(); // Mã số sinh viên
            $table->string('name'); // Tên sinh viên
            $table->string('email')->unique(); // Email
            $table->string('phone')->nullable(); // Số điện thoại
            $table->string('class'); // Lớp
            $table->string('department'); // Khoa
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
