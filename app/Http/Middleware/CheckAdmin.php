<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
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
            // تحقق من أن المستخدم هو Admin
            if (auth()->user()->is_admin == 0) {
                return $next($request);
            }
        }

        // إعادة التوجيه إذا كان المستخدم ضيفًا (غير مسجل دخول)
        return redirect()->route('register')
                         ->with(['message' => 'Please log in to access this page.']);
    }
}
