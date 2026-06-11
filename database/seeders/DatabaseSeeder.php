<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Используем updateOrCreate, чтобы не дублировать админа при перезапуске
        User::updateOrCreate(
            ['email' => 'admin@edu.com'],
            [
                'name'     => 'Administrator', // Добавлено поле name
                'password' => Hash::make('course2026'),
                'is_admin' => true,
            ]
        );

        // Запускаем ваш сидер с уроками после создания админа
        $this->call([
            LessonSeeder::class,
        ]);
    }
}