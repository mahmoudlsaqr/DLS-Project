<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    */

    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this ->middleware('guest') ->except('logout');
    }

    public function authenticated($request, $user)
    {
        $request ->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:4',
        ]);

//        $user ->update([
//            'last_active' => now(),
//        ]);
    }

    protected function username()
    {
        $login_value = request('username'); # Get input value
        $this ->username = filter_var($login_value, FILTER_VALIDATE_EMAIL) ? 'email' : 'username'; # Check if it's an e-mail or just a text
        request() ->merge([$this ->username => $login_value]); # Merge values
        return property_exists($this, 'username') ? $this ->username : 'email'; # Return login type
    }

    public function logout(Request $request)
    {
        $this ->guard() ->logout();
        $request ->session() ->invalidate();
        return redirect('/login');
    }
}
