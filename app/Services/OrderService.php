<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class OrderService
{
  public function createOrder(int $courseId): Order
{
    $course = Course::findOrFail($courseId);

    // Условие: если дата сейчас ПОСЛЕ начала ИЛИ ПОСЛЕ окончания курса — запись закрыта
    if (now()->greaterThan($course->start_date) || now()->greaterThan($course->end_date)) {
        throw new \Exception("Course registration is closed.");
    }

    return Order::create([
        'user_id' => Auth::id(),
        'course_id' => $course->id,
        'payment_status' => Order::STATUS_PENDING // Убедитесь, что эта константа определена в модели Order
    ]);
 }
}