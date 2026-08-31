<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*$user = Auth::user();
        if ($user && $user->status == 0) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive. Please contact admin.');
        }*/
        
        return $next($request);
    }
}
