<?php

namespace App\Http\Requests;

class RegisterRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // 1. Сюда пишем правила (валидацию)
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:3',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[_#!%]/'
            ],
        ];
    }

    // 2. Сюда пишем тексты сообщений (если нужны кастомные)
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required',
            'email.unique' => 'This email is already taken',
            'password.regex' => 'Password must contain uppercase, lowercase, digit and special char (_ # ! %)',
        ];
    }
}