<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!session()->has('admin_logged_in') || !session('admin_logged_in')) {
            // Nếu chưa đăng nhập, chuyển hướng về trang login
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Kiểm tra thêm quyền admin nếu cần
        if (session()->has('user_role') && session('user_role') !== 'admin') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang admin.');
        }

        return $next($request);
    }
} 