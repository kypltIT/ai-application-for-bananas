<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class SePayWebhookListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */

    //Allows you to handle the event.
    public function handle($event): void
    {
        try {
            if ($event['transferType'] === 'in') {
                $orderCode = $event['code'];
                $order = Order::where('order_code', $orderCode)->first();
                if ($order) {
                    $order->status = 'processing';
                    $order->save();
                    $transaction = Transaction::where('order_id', $order->id)->first();
                    $transaction->status = 'paid';
                    $transaction->save();
                } else {
                    Log::error("Order not found", ['order_code' => $orderCode]);
                }
            } else {
            }
        } catch (\Exception $e) {
            Log::error("Error handling SePay Webhook", ['message' => $e->getMessage()]);
        }
    }
}
