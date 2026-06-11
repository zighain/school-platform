<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Обработка вебхука от платежной системы
     * Требование ТЗ: Status code 204, Body отсутствует.
     */
    public function webhook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'status'   => 'required|in:success,failed',
        ]);

        if ($validator->fails()) {
            // Для вебхука лучше вернуть 400 или 422, чтобы система знала об ошибке
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::find($request->order_id);
        
        if ($order) {
            $order->update(['payment_status' => $request->status]);
        }

        // Строго по ТЗ: 204 No Content
        return response()->noContent();
    }

    /**
     * Отображение формы оплаты (симулятор)
     */
    public function paymentPage(Request $request)
    {
        $orderId = $request->query('order_id');
        
        if (!$orderId || !Order::find($orderId)) {
            return response()->json(['message' => 'Order not found or invalid'], 404);
        }

        return "
            <form action='" . url('/school-api/payment-page/process') . "' method='POST' style='max-width:300px; margin:50px auto; font-family:sans-serif;'>
                " . csrf_field() . "
                <input type='hidden' name='order_id' value='{$orderId}'>
                <h3 style='text-align:center;'>Симулятор оплаты</h3>
                <p>Заказ ID: " . htmlspecialchars($orderId) . "</p>
                <div style='margin-bottom:15px;'>
                    <label>Номер карты:</label><br>
                    <input type='text' name='card_number' placeholder='8888 0000 0000 1111' required style='width:100%; padding:8px; margin-top:5px;'>
                </div>
                <button type='submit' style='width:100%; padding:10px; background:#28a745; color:#fff; border:none; cursor:pointer; border-radius:4px;'>Оплатить</button>
                <div style='margin-top:20px; font-size:12px; color:#666;'>
                    Успех: 8888 0000 0000 1111<br>
                    Ошибка: 8888 0000 0000 2222
                </div>
            </form>
        ";
    }

    /**
     * Обработка данных формы оплаты
     */
    public function processPayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $cardNumber = str_replace(' ', '', $request->input('card_number'));

        // Логика симуляции по ТЗ
        $status = ($cardNumber === '8888000000001111') ? 'success' : 'failed';

        $order = Order::find($orderId);
        if ($order) {
            $order->update(['payment_status' => $status]);
        }

        return "
            <div style='text-align:center; font-family:sans-serif; margin-top:50px;'>
                <h2 style='color: " . ($status === 'success' ? '#28a745' : '#dc3545') . "'>
                    Статус оплаты: " . ($status === 'success' ? 'Успешно' : 'Ошибка') . "
                </h2>
                <p>Данные успешно обработаны. Вы можете закрыть эту страницу.</p>
            </div>
        ";
    }
}