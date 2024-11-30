<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Series;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showAdminPanel(){
        return view('admin.adminpanel');
    }
    public function home(){
       $movies=Movie::all();
       $series=Series::all();
       return view('admin.home',compact('movies','series'));
    }
    public function admin_page(){
        $admins=User::where('is_admin',0)->paginate(5);
        return view('admin.admins',compact('admins'));
    }
    public function searchAdmins(Request $request)
    {
    $search = $request->input('search');
    $admins = User::where('email', 'like', '%' . $search . '%')->get();

    return response()->json($admins);
    }
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->id,
        ]);

        $admin = User::find($request->id);
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->save();

        return response()->json(['success' => true]);
    }
    public function destroy($adminId)
    {
        // البحث عن المستخدم
        $admin = User::find($adminId);

        // تحقق إذا كان المستخدم موجودًا
        if ($admin) {
            $admin->delete();  // حذف المستخدم من الـ DB

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 404);  // في حال لم يتم العثور على المستخدم
        }
    }
    public function index(Request $request)
    {
        $search = $request->input('search');  // الحصول على قيمة البحث

        // استعلام قاعدة البيانات مع الفلترة بناءً على البحث
        $admins = User::when($search, function ($query, $search) {
            return $query->where('email', 'like', '%' . $search . '%');
        })->paginate(10); // يمكنك تعديل الباجينايشن على حسب الحاجة

        // إذا كان الطلب AJAX
        if ($request->ajax()) {
            $tableRows = view('admin.adminpanel-rows', compact('admins'))->render();
            $pagination = view('pagination::bootstrap-5', ['paginator' => $admins])->render();

            return response()->json([
                'tableRows' => $tableRows,
                'pagination' => $pagination
            ]);
        }

        // إذا كان الطلب عادي
        return view('admin.adminpanel', compact('admins'));
    }
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',  // تحقق من وجود البريد مباشرة
            'password' => 'required|min:8',
        ], [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email',
            'email.email' => 'The email address is invalid',
            'email.unique' => 'This email is already taken', // رسالة في حال وجود البريد
            'password.required' => 'Please enter your password',
            'password.min' => 'Password must be at least 8 characters',
        ]);
        // إنشاء المستخدم
        $admin=new User();
        $admin->name=$validated['name'];
        $admin->email=$validated['email'];
        $admin->password=Hash::make($validated['password']);
        $admin->is_admin=0;
        $admin->save();

        return back()->with('success', 'Admin added successfully!');

        // إعادة التوجيه أو استجابة عند النجاح
        return redirect()->route('admin_page')->with('success', 'User created successfully');

    }
}