<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * Redirect the user to a after login page acccording
     * with the user type (admin or "client")
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /*
        if (Auth::guard($guard)->check()) {
            return redirect('/');
        }
        */

        if (Auth::guard('admin')->check() || Auth::check() )
        {
          return redirect ('/admin');
        }else if(Auth::guard('web')->check())
        {
          return redirect('/');
        }


        return $next($request);
    }
}
