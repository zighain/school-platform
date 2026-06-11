<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonRequest; // Импортируем ваш FormRequest
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $lessons = $course->lessons()->paginate(5);
        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    public function create($courseId)
    {
        $course = Course::findOrFail($courseId);
        if ($course->lessons()->count() >= 5) {
            return redirect()->route('admin.courses.lessons.index', $courseId)
                             ->withErrors(['error' => 'В курсе не может быть более 5 уроков.']);
        }
        return view('admin.lessons.create', compact('course'));
    }

    // Используем StoreLessonRequest вместо обычного Request
    public function store(StoreLessonRequest $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        // Получаем данные, которые прошли валидацию в StoreLessonRequest
        $data = $request->validated();
        
        // Добавляем course_id, так как мы создаем урок через связь
        $data['course_id'] = $course->id;

        $course->lessons()->create($data);

        return redirect()->route('admin.courses.lessons.index', $courseId)
                         ->with('success', 'Урок добавлен!');
    }

    public function destroy($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        
        // ТЗ: Удаление только при отсутствии активных записей
        $hasActiveStudents = $course->orders()->whereIn('payment_status', ['success', 'pending'])->exists();
        if ($hasActiveStudents) {
            return redirect()->back()->withErrors(['error' => 'Нельзя удалить: на курс записаны студенты.']);
        }

        Lesson::where('id', $lessonId)->where('course_id', $courseId)->firstOrFail()->delete();
        return redirect()->back()->with('success', 'Урок удален.');
    }
}