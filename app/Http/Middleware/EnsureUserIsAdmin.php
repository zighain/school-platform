<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, авторизован ли пользователь и является ли он администратором
        if ($request->user() && $request->user()->email === 'admin@edu.com') {
            return $next($request);
        }

        // Если нет — выбрасываем 403 ошибку
        abort(403, 'Forbidden for you');
    }
}