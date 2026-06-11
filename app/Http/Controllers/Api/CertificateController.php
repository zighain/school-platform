<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class CertificateController extends Controller
{
    /**
     * Генерация сертификата и сохранение в БД
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:users,id',
            'course_id'  => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid fields', 
                'errors'  => $validator->errors()
            ], 422);
        }

        $serviceHost = rtrim(env('SERVICE_HOST', 'https://api.certificate-service.com'), '/');
        
        // Отправка запроса к внешнему сервису
        $response = Http::withHeaders([
            'ClientId'     => 'admin@edu.com', 
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$serviceHost}/create-sertificate", [
            'student_id' => $request->student_id,
            'course_id'  => $request->course_id,
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'External service error'], 500);
        }

        // Формирование номера: 6 символов сервиса + 5 случайных + '1' (валидный)
        $servicePart = (string) $response->json('course_number');
        $randomPart  = str_pad((string) mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $fullNumber  = substr($servicePart, 0, 6) . $randomPart . '1';

        // Обновление записи в таблице orders
        Order::where('user_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->update(['certificate_number' => $fullNumber]);

        return response()->json(['certificate_number' => $fullNumber], 200);
    }

    /**
     * Проверка действительности сертификата
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sertikate_number' => 'required|string|size:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid fields', 
                'errors'  => $validator->errors()
            ], 422);
        }

        // Логика проверки: заканчивается на '1' - успех
        $lastDigit = substr($request->sertikate_number, -1);
        $status = ($lastDigit === '1') ? 'success' : 'failed';

        return response()->json(['status' => $status], 200);
    }
}