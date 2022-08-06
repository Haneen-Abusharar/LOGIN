<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

//use Illuminate\Support\Facades\Auth;
class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $url = $request->url();
        $str = explode('/', $url);
        $public_routes = [
            "login", "check", "register"
        ];

        if (in_array($str[4], $public_routes))
            return $next($request);
        else {
            $value = $request->header('auth');
            if ($value) {
                if ($user = User::where('token', $value)->first()) {
                    // dd($user);
                    Auth::login($user);
                    return $next($request);
                } else
                    return response()->json(['error'=>'Unauthorised'], 401);

            } else
                return response()->json(['error'=>'Unauthorised'], 401);

        }
    }
}
