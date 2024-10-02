<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // Khóa ngoại liên kết với bảng students
            $table->string('student_name');
            $table->string('student_email');
            $table->text('message'); // Nội dung vấn đề mà sinh viên gặp phải
            $table->timestamps(); // Thời gian gửi vấn đề

            // Tạo khóa ngoại liên kết với bảng students
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_issues');
    }
}
