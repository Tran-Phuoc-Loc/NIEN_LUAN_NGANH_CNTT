<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        // Lấy thông tin user đang đăng nhập
        $user = auth()->user();

        // Chia sẻ biến $user tới tất cả view
        View::share('user', $user);
        $notifications = Notification::all(); // Hoặc truy vấn theo yêu cầu của bạn
        View::share('notifications', $notifications);
    }
}
