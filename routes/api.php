<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CertificateController;

Route::post('/registr', [AuthController::class, 'register']);
Route::post('/auth', [AuthController::class, 'login']);
Route::post('/check-sertificate', [OrderController::class, 'checkCertificate']);
Route::post('/payment-webhook', [OrderController::class, 'webhook']); 

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::post('/courses/{id}/buy', [OrderController::class, 'buy']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'cancel']);
});