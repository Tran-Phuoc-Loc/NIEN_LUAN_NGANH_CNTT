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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề của tin tức
            $table->text('content'); // Nội dung tin tức
            $table->string('image')->nullable(); // Ảnh minh họa (tuỳ chọn)
            $table->timestamp('published_at')->nullable(); // Ngày giờ đăng tin
            $table->timestamps(); // Thêm các cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
