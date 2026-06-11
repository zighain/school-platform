<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.index');
        }

        return back()->withErrors(['email' => 'Неверные данные'])->withInput();
    }

    public function index() 
    { 
        $courses = Course::paginate(5); 
        return view('admin.courses.index', compact('courses')); 
    }

    public function printCertificate($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Используем try-catch, чтобы приложение не падало при отсутствии интернета
        try {
            $response = Http::timeout(5)->withHeaders([
                'ClientId'     => 'admin@edu.com',
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(rtrim(env('SERVICE_HOST', 'https://api.certificate-service.com'), '/') . '/create-sertificate', [
                'student_id' => $order->user_id,
                'course_id'  => $order->course_id
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $servicePart = substr($data['course_number'] ?? '000000', 0, 6);
            } else {
                // Если сервис вернул ошибку, используем дефолтное значение для разработки
                $servicePart = '123456';
            }
        } catch (\Exception $e) {
            // Если сервис недоступен, используем заглушку для демонстрации работы интерфейса
            $servicePart = '123456';
        }

        // Логика формирования номера сертификата (12 символов)
        $randomPart = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $certNumber = $servicePart . $randomPart . '1';
        
        $order->update(['certificate_number' => $certNumber]);
        
        return view('admin.certificate.show', compact('order', 'certNumber'));
    }

    public function students(Request $request)
    {
        $query = Order::with(['user', 'course']);
        
        if ($request->filled('course_id')) { 
            $query->where('course_id', $request->course_id); 
        }
        
        $students = $query->paginate(10);
        $courses = Course::all(); 
        
        return view('admin.students.index', compact('students', 'courses'));
    }
}