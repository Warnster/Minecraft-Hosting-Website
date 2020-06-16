<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->permission == 1) {
            return $next($request);
        }

        return redirect()->route('/'); // If user is not an admin.
    }
}
