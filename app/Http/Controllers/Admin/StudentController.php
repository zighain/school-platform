<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $courses = Course::all();
        $query = Order::with(['user', 'course']);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.students.index', compact('orders', 'courses'));
    }

    public function printCertificate($orderId)
    {
        $order = Order::with(['user', 'course'])->findOrFail($orderId);

        if ($order->payment_status !== 'success') {
            return redirect()->back()->withErrors(['error' => 'Невозможно выдать сертификат. Курс не оплачен.']);
        }

        if ($order->certificate_number) {
            return view('admin.students.certificate', compact('order'));
        }

        $serviceHost = env('SERVICE_HOST', 'https://api.certificate-service.com');

        try {
            $response = Http::withHeaders([
                'ClientId'     => 'admin@edu.com', 
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(rtrim($serviceHost, '/') . '/create-sertificate', [
                'student_id' => $order->user_id,
                'course_id'  => $order->course_id,
            ]);

            if ($response->failed()) {
                return redirect()->back()->withErrors(['error' => 'Сервер сертификации вернул ошибку при создании номера.']);
            }

            $courseNumber = $response->json('course_number');

            if (!$courseNumber || strlen((string)$courseNumber) !== 6) {
                return redirect()->back()->withErrors(['error' => 'Получен неверный формат префикса сертификата.']);
            }

            $randomPart = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $finalCertificateNumber = $courseNumber . $randomPart . '1';

            $order->update([
                'certificate_number' => $finalCertificateNumber
            ]);

            return view('admin.students.certificate', compact('order'));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Не удалось связаться с сервером сертификации: ' . $e->getMessage()]);
        }
    }
}