<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        if (Session::has('lastActivity') && (time() - Session::get('lastActivity') > 21600)) { // 6 ساعات
            Auth::logout();
            return redirect('/')->with('info', 'Session was terminated due to inactivity.');
        }

        Session::put('lastActivity', time());

        return $next($request);
    }
}
