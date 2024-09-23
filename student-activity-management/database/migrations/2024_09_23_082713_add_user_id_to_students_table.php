<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Thêm trường user_id
            $table->unsignedBigInteger('user_id')->nullable()->after('id');

            // Thêm khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Cập nhật dữ liệu cho trường user_id
        $students = DB::table('students')->get();

        foreach ($students as $student) {
            // Tìm user tương ứng dựa trên một tiêu chí nào đó
            $user = DB::table('users')->where('student_id', $student->student_id)->first();

            if ($user) {
                DB::table('students')->where('id', $student->id)->update(['user_id' => $user->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Xóa khóa ngoại
            $table->dropColumn('user_id'); // Xóa trường user_id
        });

    }
};
