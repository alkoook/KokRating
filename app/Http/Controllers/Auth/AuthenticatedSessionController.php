<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {

        // التحقق من البيانات المدخلة
        $validate = $request->validate([
            'em' => 'required|email',
            'pass' => 'required|min:8',
        ], [
            'em.required' => 'Enter Email',
            'em.email' => 'Email is Invalid',
            'pass.required' => 'Enter Password',
            'pass.min' => 'Password is too Short',
        ]);

        // التحقق إذا كان المستخدم موجودًا في قاعدة البيانات
        $user = User::where('email', $validate['em'])->first();

        if ($user) {
            // محاولة تسجيل الدخول باستخدام Auth::attempt
            $credentials = ['email' => $validate['em'], 'password' => $validate['pass']];

            if (Auth::attempt($credentials)) {
                // تسجيل الدخول ناجح
                $user = Auth::user(); // الحصول على المستخدم الحالي

                // التحقق من نوع المستخدم
                if ($user->is_admin == 0) {
                    return redirect()->route('admin.home');
                } elseif ($user->is_admin == 1) {
                    return redirect()->route('user.home');
                }
            } else {
                // إذا كانت كلمة المرور غير صحيحة
                return redirect()->route('register')
                    ->withErrors(['message' => 'Password is not correct']);
            }
        } else {
            // إذا كان البريد الإلكتروني غير موجود
            return redirect()->route('register')
                ->withErrors(['message' => 'Email not registered']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
    // تسجيل الخروج
    Auth::guard('web')->logout();

    // إلغاء الجلسة وتجديد التوكن
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // التحقق من نوع المستخدم
    if (auth()->check() && auth()->user()->is_admin==0) {
        // إذا كان Admin، توجيهه إلى صفحة الـ Admin
        return redirect()->route('admin.home');
    } else {
        // إذا كان User، توجيهه إلى صفحة الـ Home أو صفحة الـ User
        return redirect()->route('user.home');
    }}
}