<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Очищаем таблицу перед наполнением
        Lesson::truncate();

        // 2. Создаем 5 уроков
        for ($i = 1; $i <= 5; $i++) {
            Lesson::create([
                'course_id'  => 1,
                'name'       => "Урок номер $i",
                'content'    => "Текстовое содержание урока номер $i",
                'video_link' => 'https://super-tube.cc/video/v23189',
                'hours'      => 1, 
            ]);
        }
    }
}