<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserStatus
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if(Auth::check()){
            if(Auth::user()->status != 'true'){
                Auth::logout();
                return redirect('/login')->with('Inactive', __('Your Account Disabled'));
            }
        }
        return $response;
    }
}
