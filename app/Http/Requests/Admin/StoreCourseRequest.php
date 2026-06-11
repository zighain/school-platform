<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\User;

class StoreCourseRequest extends FormRequest
{
    /**
     * Определяет, авторизован ли пользователь для выполнения данного запроса.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = $this->user();
        
        // Проверка: пользователь существует и имеет статус администратора
        return $user !== null && $user->isAdmin();
    }

    /**
     * Правила валидации согласно ТЗ:
     * - name: обязат., max 30, уникальное
     * - description: необязат., max 100
     * - hours: обязат., int, max 10
     * - price: обязат., формат xx.xx, min 100
     * - dates: обязат., формат дд-мм-гггг
     * - img: обязат., jpg/jpeg, max 2000 Кб
     */
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:30|unique:courses,name',
            'description' => 'nullable|string|max:100',
            'hours'       => 'required|integer|min:1|max:10',
            'price'       => ['required', 'numeric', 'min:100', 'regex:/^\d+(\.\d{1,2})?$/'],
            'start_date'  => 'required|date_format:d-m-Y',
            'end_date'    => 'required|date_format:d-m-Y|after_or_equal:start_date',
            'img'         => 'required|image|mimes:jpeg,jpg|max:2000',
        ];
    }

    /**
     * Переопределение метода обработки ошибок валидации.
     */
    protected function failedValidation(Validator $validator)
    {
        // Если запрос ожидает JSON (API), возвращаем структуру по ТЗ (422)
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Invalid fields',
                'errors'  => $validator->errors()
            ], 422));
        }

        // Для админки (Blade) вызываем стандартный редирект с ошибками в сессии
        parent::failedValidation($validator);
    }
}