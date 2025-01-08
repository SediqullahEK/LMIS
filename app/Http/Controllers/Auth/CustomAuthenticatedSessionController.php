<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;


class CustomAuthenticatedSessionController extends  AuthenticatedSessionController
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'user_name' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'user_name.required' => 'نام کاربری لازمی میباشد',
                'password.required' => 'رمز عبور لازمی میباشد'
            ]
        );

        if (! Auth::attempt($request->only('user_name', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'user_name' => __('نام کاربر یا رمز عبور اشتباه است !'),
            ]);
        }
        if (!Auth::user()->first_logged_in) {
            return redirect()->route('first_login_change_password');
        }

        $request->session()->regenerate();
        $user = User::find(auth()->user()->id);
        $user->last_login_on = now();
        $user->save();
        logActivity('login', 'User', $user->id);


        return redirect()->intended(config('fortify.home'));
    }
}
