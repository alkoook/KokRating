<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تحقق من حالة المصادقة
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->is_admin == 1) {
                return $next($request); // السماح بالوصول إلى المسارات التي تبدأ بـ 'user.'
            }
        }

        // إعادة التوجيه إذا كان المستخدم ضيفًا (غير مسجل دخول)
        return redirect()->route('register')->with(['message' => 'Please log in to access this page.']);
    }
}