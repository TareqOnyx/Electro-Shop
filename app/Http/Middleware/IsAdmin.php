<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role == 1) {
            return $next($request);
        }

        return redirect('/'); // ليس admin
    }
}
