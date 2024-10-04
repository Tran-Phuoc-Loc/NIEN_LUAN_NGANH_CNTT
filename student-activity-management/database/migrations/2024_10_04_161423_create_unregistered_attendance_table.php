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
        Schema::create('unregistered_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();  // Student ID
            $table->string('full_name');             // Tên sinh viên
            $table->string('email')->nullable();     // Email
            $table->string('phone')->nullable();     // Điện thoại
            $table->string('department')->nullable(); // Khoa
            $table->string('batch')->nullable();     // Khóa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unregistered_attendance');
    }
};
