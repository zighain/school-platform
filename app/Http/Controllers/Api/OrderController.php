<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function buy($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json([
                'message' => 'Invalid fields',
                'errors'  => ['course_id' => ['Course not found']]
            ], 422);
        }

        $now = Carbon::now();
        $start = Carbon::parse($course->start_date);
        $end = Carbon::parse($course->end_date);

        if ($now->greaterThanOrEqualTo($start) || $now->greaterThan($end)) {
            return response()->json([
                'message' => 'Invalid fields',
                'errors'  => ['course' => ['Нельзя записаться на курс, который уже начался или закончился.']]
            ], 422);
        }

        $existingOrder = Order::where('user_id', Auth::id())
                              ->where('course_id', $course->id)
                              ->exists();
                            
        if ($existingOrder) {
            return response()->json([
                'message' => 'Invalid fields', 
                'errors' => ['course' => ['Вы уже записаны на этот курс.']]
            ], 422);
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'payment_status' => 'pending'
        ]);

        return response()->json(['pay_url' => url('/school-api/payment-page?order_id=' . $order->id)], 200);
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('course')->paginate(5);

        $data = collect($orders->items())->map(function ($order) {
            return [
                'id'             => $order->id,
                'payment_status' => $order->payment_status, 
                'course' => [
                    'id'          => $order->course->id,
                    'name'        => $order->course->name,
                    'description' => $order->course->description,
                    'hours'       => $order->course->hours,
                    'img'         => asset('storage/' . $order->course->img),
                    'start_date'  => Carbon::parse($order->course->start_date)->format('d-m-Y'),
                    'end_date'    => Carbon::parse($order->course->end_date)->format('d-m-Y'),
                    'price'       => number_format((float)$order->course->price, 2, '.', ''),
                ]
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'total'    => $orders->lastPage(), 
                'current'  => $orders->currentPage(), 
                'per_page' => $orders->perPage(),
            ]
        ], 200);
    }

    public function cancel($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$order) {
            return response()->json([
                'message' => 'Invalid fields',
                'errors'  => ['order_id' => ['Order not found']]
            ], 422);
        }

        if (in_array($order->payment_status, ['pending', 'failed'])) {
            $order->delete();
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'was payed'], 418);
    }

   public function webhook(Request $request)
{
    // Валидация обязательна по ТЗ
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'status' => 'required|in:success,failed',
    ]);

    $order = Order::find($request->order_id);
    $order->update(['payment_status' => $request->status]);

    return response()->json(null, 204); // Код 204 согласно ТЗ
}

    public function checkCertificate(Request $request)
    {
        $number = $request->input('sertikate_number');
        
        if (!$number) {
            return response()->json([
                'message' => 'Invalid fields',
                'errors'  => ['sertikate_number' => ['The field is required.']]
            ], 422);
        }

        $status = (substr((string)$number, -1) === '1') ? 'success' : 'failed';
        return response()->json(['status' => $status], 200);
    }
}