<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;
use Livewire\Attributes\On;

class KitchenOrders extends Component
{
    public function render()
    {
        $timezone = config('app.timezone', 'Europe/Bratislava');
        $today = Carbon::now($timezone)->toDateString();

        // Only show paid orders, split into processing and finished
        $processingOrders = Order::with('items.product', 'items.additions')
            ->where('payment_status', 'paid')
            ->where('status', 'processing')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'asc')
            ->get();

        $finishedOrders = Order::with('items.product', 'items.additions')
            ->where('payment_status', 'paid')
            ->whereIn('status', ['completed', 'cancelled'])
            ->whereDate('created_at', $today)
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return view('livewire.kitchen-orders', [
            'processingOrders' => $processingOrders,
            'finishedOrders' => $finishedOrders
        ]);
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        
        // When kitchen completes an order, mark it ready for waiter
        if ($status === 'completed') {
            $order->waiter_status = 'ready';
        }
        
        $order->save();

        session()->flash('success', 'Objednávka #' . $order->id . ' aktualizovaná na ' . $status . '.');
    }
}
