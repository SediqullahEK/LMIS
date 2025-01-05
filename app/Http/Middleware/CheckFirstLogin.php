<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFirstLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if the user's first_logged_in field is not true
        if (!$user || !$user->first_logged_in) {
            return redirect()->route('first_login_change_password')
                ->with('error', 'لطفا قبل از استفاده از سیستم رمز عبور تان را تغییر بدهید!');
        }
        return $next($request);
    }
}
