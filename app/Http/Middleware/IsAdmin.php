<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // إذا المستخدم مسجل دخول و role = 1 (admin)
        if (auth()->check() && auth()->user()->role == 1) {
            return $next($request);
        }

        // إذا مش admin أو مش مسجل دخول → رجعه للصفحة الرئيسية
        return redirect('index');
    }
}
