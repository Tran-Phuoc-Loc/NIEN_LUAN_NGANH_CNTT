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
        Schema::table('students', function (Blueprint $table) {
            // Xóa các cột này chỉ khi chúng tồn tại
            if (Schema::hasColumn('students', 'class')) {
                $table->dropColumn('class');
            }
            if (Schema::hasColumn('students', 'department')) {
                $table->dropColumn('department');
            }

            // Thêm các cột mới
            $table->date('joining_date')->nullable()->after('phone');
            $table->string('card_issuing_place')->nullable()->after('joining_date');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('class'); // Phục hồi các cột cũ
            $table->string('department');
            $table->dropColumn(['joining_date', 'card_issuing_place']); // Xóa các cột mới
        });
    }
};
