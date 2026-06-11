<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SchoolDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Создаем админа (чтобы ты мог войти в админку)
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@edu.com'],
            ['name' => 'Admin', 'password' => Hash::make('course2026')]
        );

        // 2. Создаем тестовый курс
        DB::table('courses')->updateOrInsert(
            ['id' => 2],
            ['name' => 'Тестовый курс', 'description' => 'Описание курса', 'hours' => 5, 'price' => 200, 'start_date' => '2026-06-10', 'end_date' => '2026-07-10', 'img' => 'mpic_test.jpg']
        );

        // 3. Создаем запись студента
        DB::table('orders')->updateOrInsert(
            ['id' => 1],
            ['user_id' => 1, 'course_id' => 2, 'payment_status' => 'pending', 'created_at' => now()]
        );
    }
}