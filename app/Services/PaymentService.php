<?php

namespace App\Services;

use App\Models\Order;

class PaymentService
{
    public function generatePaymentUrl(Order $order): string
    {
        return "https://payment.example.com/pay/" . $order->id;
    }

    public function handleWebhook(array $data): void
    {
        $order = Order::findOrFail($data['order_id']);

        $newStatus = $data['status'] === 'success' 
            ? Order::STATUS_SUCCESS 
            : Order::STATUS_FAILED;

        $order->update([
            'payment_status' => $newStatus
        ]);
        
    }
}