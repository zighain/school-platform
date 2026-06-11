<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!Auth::check() || ($user instanceof \App\Models\User && !$user->isAdmin())) {
            abort(403, 'Forbidden for you');
        }

        return $next($request);
    }
}