<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController;
use Illuminate\Support\Facades\Route;

// Маршруты авторизации
Route::get('/course-admin/login', [AdminController::class, 'showLoginForm'])->name('login');
Route::post('/course-admin/login', [AdminController::class, 'login']);

// Редирект на админку
Route::get('/', fn() => redirect('/course-admin'));

// Админ-панель
Route::prefix('course-admin')->middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    
    // Ресурс курсов
    Route::resource('courses', CourseController::class)->names('admin.courses');
    
    // Ресурс уроков с явным именованием для совместимости с вашим шаблоном
    Route::resource('courses.lessons', LessonController::class)
        ->shallow()
        ->names([
            'index'   => 'admin.courses.lessons.index',
            'create'  => 'admin.courses.lessons.create',
            'store'   => 'admin.courses.lessons.store',
            'show'    => 'admin.courses.lessons.show',
            'edit'    => 'admin.courses.lessons.edit',
            'update'  => 'admin.courses.lessons.update',
            'destroy' => 'admin.courses.lessons.destroy',
        ]);
    
    // Список студентов
    Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
    
    // Маршрут для печати сертификата (имя совпадает с вызовом в Blade)
    Route::get('/students/print/{order}', [AdminController::class, 'printCertificate'])
        ->name('admin.courses.printCertificate');
});