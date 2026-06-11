<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:3',
                'regex:/[A-Z]/', 
                'regex:/[a-z]/',
                'regex:/[0-9]/', 
                'regex:/[_#!%]/' 
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid fields',
                'errors' => $validator->errors()
            ], 422);
        }

        // Извлекаем имя из email (например, из "student123@gmail.com" получится "student123")
        $username = explode('@', $request->email)[0];

        // Создаем пользователя, подставляя сгенерированное имя
        User::create([
            'name' => $username, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['success' => true], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->loginErrorResponse();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->loginErrorResponse();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        if (str_contains($token, '|')) {
            $token = explode('|', $token, 2)[1];
        }

        return response()->json(['token' => $token], 200);
    }

    private function loginErrorResponse()
    {
        return response()->json([
            'message' => 'Invalid data',
            'errors' => [
                'email' => ['Invalid data']
            ]
        ], 422);
    }
}