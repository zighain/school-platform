<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            // Маршруты API
            Route::middleware('api')
                ->prefix('school-api')
                ->group(base_path('routes/api.php'));

            // Маршруты Web (Админка и прочее)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Регистрация алиасов для Middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        
        // Исключение CSRF защиты для вебхуков
        $middleware->validateCsrfTokens(except: [
            'school-api/payment-webhook',
            'school-api/payment-page/process',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Кастомная обработка ошибок валидации для API
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('school-api/*')) {
                // Специфичное сообщение для эндпоинта авторизации
                if ($request->is('school-api/auth')) {
                    return response()->json([
                        'message' => 'Invalid data',
                        'errors'  => $e->errors()
                    ], 422);
                }

                // Стандартное сообщение для остальных API эндпоинтов
                return response()->json([
                    'message' => 'Invalid fields',
                    'errors'  => $e->errors()
                ], 422);
            }
        });

        // Обработка ошибок доступа (403) для API
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('school-api/*')) {
                return response()->json(['message' => 'Forbidden for you'], 403); 
            }
        });
    })->create();