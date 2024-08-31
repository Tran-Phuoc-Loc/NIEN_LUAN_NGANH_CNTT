<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Giới thiệu

Đây là một web quản lý tham gia đoàn/hội cho sinh viên. Giúp các cán bộ đoàn quản lý dễ dàng hơn trong việc theo dõi thông tin đoàn viên, đăng ký tham gia các hoạt động, và đánh giá hiệu quả của các chương trình hoạt động. Đây là nơi các sinh viên có thể đăng ký tham gia các hoạt động, theo dõi các thông tin.

## Yêu cầu hệ thống

- **PHP**: >= 8.2
- **Laravel**: >= 11.x
- **Composer**: >= 2.7.x
- **Node.js và npm**:20.17.x và 10.8.x (Để quản lý các gói frontend)
- **Chú ý**: Ứng dụng đã được thử nghiệm với các phiên bản trên.

## Cài đặt

1. **Clone repository**:
   ```bash
   git clone https://github.com/Tran-Phuoc-Loc/NIEN_LUAN_NGANH_CNTT.git
   cd student-activity-management 
2. **Cài đặt các phụ thuộc PHP**:
   - **Hướng dẫn**: Cần cài đặt Composer để chạy được [tải Composer chính thức ở đây](https://getcomposer.org/download/) bạn có thể tìm hiểu thêm về Composer.
   ```bash
   composer install
4. **Cài đặt JavaScript**:
   - **Hướng dẫn**: Cần cài đặt Node.js để chạy được [tải Node.js chính thức ở đây](https://nodejs.org/en) bạn có thể tìm hiểu thêm về Node.js.
    - Sau đó chạy lệnh bên dưới
   ```bash
   npm install && npm run build

5. **Tạo file .env và cấu hình kết nối cơ sở dữ liệu**:
   ```bash
   cp .env.example .env
6. **Chạy để có cơ sở dữ liệu**:
   ```bash
   php artisan make:seeder AdminSeeder
   php artisan make:seeder UserSeeder
   php artisan db:seed
   
7. **Chạy migrations để tạo các bảng trong cơ sở dữ liệu**:
   ```bash
   php artisan migrate
9. **Tạo key ứng dụng**:
   ```bash
   php artisan key:generate
11. **Chạy ứng dụng**:
   ```bash
   php artisan serve
``` 
## Sử dụng

-
-
-
-
## Các tính năng chính

## Hỗ trợ
