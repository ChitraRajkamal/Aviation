<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            return redirect('/');
        }
        $user = Auth::user();
        if ($user && $user->status == -1) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is deleted.');
        }
        if ($user && $user->status == 0) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is inactive. Please contact admin.');
        }
        if(lms_is_organization() && !lms_is_organization_admin()){
            $routeName = $request->route()->getName();
            $menus = lms_organization_menus(auth()->user()->role_id);
            if(!in_array($routeName, $menus)){
                abort(403, 'Unauthorized Access');
            }
        }
        
        return $next($request);
    }
}
