<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('activities')->insert([
            [
                'name' => 'Hội thảo phát triển kỹ năng',
                'description' => 'Hội thảo giúp phát triển các kỹ năng mềm cần thiết.',
                'date' => Carbon::now()->addDays(10), // Ngày tổ chức
                'location' => 'Hôi Trường Rùa',
                'registration_start' => Carbon::now()->addDays(1), // Ngày bắt đầu đăng ký
                'registration_end' => Carbon::now()->addDays(9), // Ngày kết thúc đăng ký
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Buổi gặp gỡ cựu sinh viên',
                'description' => 'Gặp gỡ các cựu sinh viên để chia sẻ kinh nghiệm.',
                'date' => Carbon::now()->addDays(20), // Ngày tổ chức
                'location' => 'Nhà Sinh Hoạt Văn Hóa Khu A',
                'registration_start' => Carbon::now()->addDays(5), // Ngày bắt đầu đăng ký
                'registration_end' => Carbon::now()->addDays(19), // Ngày kết thúc đăng ký
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Thêm dữ liệu mẫu khác nếu cần
        ]);
    }
}
