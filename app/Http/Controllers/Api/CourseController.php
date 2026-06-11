<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::paginate(5);

        $data = $courses->map(function ($course) {
            return [
                'id'          => $course->id,
                'name'        => $course->name,
                'description' => $course->description,
                'hours'       => $course->hours,
                'img'         => asset('storage/' . $course->img),
                'start_date'  => Carbon::parse($course->start_date)->format('d-m-Y'),
                'end_date'    => Carbon::parse($course->end_date)->format('d-m-Y'),
                'price'       => number_format((float)$course->price, 2, '.', ''),
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'total'    => $courses->lastPage(),
                'current'  => $courses->currentPage(), 
                'per_page' => $courses->perPage(),
            ]
        ], 200);
    }

public function show($courseId)
    {
        $course = Course::with('lessons')->find($courseId);

        if (!$course) {
            return response()->json([
                'message' => 'Invalid fields',
                'errors'  => ['course_id' => ['Course not found']]
            ], 422);
        }

        $data = $course->lessons->map(function ($lesson) {
            return [
                'id'          => $lesson->id,
                'name'        => $lesson->name,        
                'description' => $lesson->content,    
                'video_link'  => $lesson->video_link,
                'hours'       => $lesson->hours,
            ];
        });

        return response()->json(['data' => $data], 200);
    }

    public function buy($courseId)
    {
        $course = Course::find($courseId);
        $user = Auth::user();

        if (!$course) {
            return response()->json([
                'message' => 'Invalid fields',
                'errors'  => ['course_id' => ['Course not found']]
            ], 422);
        }

        $now = Carbon::now();
        $startDate = Carbon::parse($course->start_date);
        $endDate = Carbon::parse($course->end_date);

        if ($now->greaterThanOrEqualTo($startDate) || $now->greaterThan($endDate)) {
            return response()->json([
                'message' => 'Invalid fields',
                'errors'  => [
                    'course' => ['Нельзя записаться на курс, который уже начался или закончился.']
                ]
            ], 422);
        }

        $existingOrder = Order::where('user_id', $user->id)
                              ->where('course_id', $course->id)
                              ->first();

        if ($existingOrder) {
             return response()->json([
                 'message' => 'Invalid fields',
                 'errors'  => ['course' => ['Вы уже записаны на этот курс.']]
             ], 422);
        }

        $order = Order::create([
            'user_id'        => $user->id,
            'course_id'      => $course->id,
            'payment_status' => 'pending',
        ]);

        $payUrl = url("/school-api/payment-page?order_id=" . $order->id);

        return response()->json([
            'pay_url' => $payUrl
        ], 200);
    }
}