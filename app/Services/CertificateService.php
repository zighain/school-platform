<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Exception;

class CertificateService
{
    public function issueCertificate(Order $order): string
    {
        $url = config('services.cert.host') . '/create-sertificate';
        
        $response = Http::withHeaders([
            'ClientId' => config('services.cert.login'),
            'Accept'   => 'application/json',
        ])->post($url, [
            'student_id' => $order->user_id,
            'course_id'  => $order->course_id,
        ]);

        if ($response->failed()) {
            throw new Exception("Certificate service unavailable");
        }

        $data = $response->json();
        
        $randomPart = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT) . '1';
        
        return substr($data['course_number'], 0, 6) . $randomPart;
    }
}