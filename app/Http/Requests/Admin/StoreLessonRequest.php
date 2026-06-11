<?php

namespace App\Http\Requests\Admin;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = Auth::user();
        return Auth::check() && $user->isAdmin();
    }

    public function rules(): array
    {
        return [
            // course_id передается из маршрута, валидируем его наличие
            'course_id' => [
                'nullable', 
                'exists:courses,id',
            ],
            'name'       => 'required|string|max:50',
            'content'    => 'required|string', // ИСПРАВЛЕНО: было description
            'video_link' => [
                'nullable',
                'url',
                'regex:/^https:\/\/super-tube\.cc\/video\/[a-zA-Z0-9]+$/'
            ],
            'hours'      => 'required|integer|min:1|max:4',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $courseId = $this->route('course'); // ID из URL
            // Проверка лимита в 5 уроков
            if ($courseId && Lesson::where('course_id', $courseId)->count() >= 5) {
                $validator->errors()->add('name', 'В этом курсе уже достигнут лимит в 5 уроков.');
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        // Если это API запрос — возвращаем JSON 422
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Invalid fields',
                'errors'  => $validator->errors()
            ], 422));
        }

        // Если это форма в админке — стандартный редирект с ошибками
        parent::failedValidation($validator);
    }
}