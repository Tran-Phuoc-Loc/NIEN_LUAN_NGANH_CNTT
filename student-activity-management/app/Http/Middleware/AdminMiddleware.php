<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // kiểm tra phải là admin không
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request);
        }
        // Chặn truy cập vào tất cả các route của admin
        if ($request->is('admin/*')) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Nếu không phải admin, chuyển hướng đến trang khác hoặc hiển thị thông báo lỗi
        return redirect('/student/dashboard')->with('error', 'Bạn không có quyền truy cập.');
    }
}
