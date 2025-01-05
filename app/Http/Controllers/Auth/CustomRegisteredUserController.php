<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterViewResponse;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Illuminate\Support\Facades\Auth;

class CustomRegisteredUserController extends RegisteredUserController
{
    public function create(Request $request): RegisterViewResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return app(RegisterViewResponse::class);
    }
}
