<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {//
        $validate=  $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'image'=>['required','mimes:jpg ,png, gif, jpeg'],
            'phone'=>['required'],

        ],[
            'name.required'=>'Enter Nme',
            'email.required'=>'Enter Email',
            'email.email'=>'Email is Invalid',
            'email.unique'=>'Email is Exists',
            'password.required'=>'Enter Password',
            'image.required'=>'Upload Image',
            'image.mimes'=>' Image problem',
            'phone.required'=>'Enter Phone No',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('user_images', $imageName, 'kok');
        } else {
            return redirect()->back()->withErrors(['photo' => 'Invalid photo file.']);
        }
        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => Hash::make($request->password),
            'phone'=>$validate['phone'],
            'image' => $path,
            'is_admin'=>'1'

        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('user.home')->with('success', 'User registered successfully.');
    }
}