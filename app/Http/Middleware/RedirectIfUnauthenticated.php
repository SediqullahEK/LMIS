<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfUnauthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (AuthenticationException $exception) {
            // Redirect unauthenticated users to login
            return redirect()->route('login')->with('error', 'Session تا منقضی شده است برای ادامه دوباره وارد شوید');
        }
    }
}
