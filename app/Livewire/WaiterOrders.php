<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class WaiterOrders extends Component
{
    public function render()
    {
        // Orders that need payment confirmation (counter payment)
        $toPay = Order::with('items.product', 'items.additions')
            ->where('payment_status', 'unpaid')
            ->where('waiter_status', 'to_pay')
            ->orderBy('created_at', 'asc')
            ->get();

        // Orders ready to be served (kitchen completed them)
        $ready = Order::with('items.product', 'items.additions')
            ->where('waiter_status', 'ready')
            ->orderBy('created_at', 'asc')
            ->get();

        // Served orders (completed by waiter)
        $served = Order::with('items.product', 'items.additions')
            ->where('waiter_status', 'served')
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->get();

        return view('livewire.waiter-orders', [
            'toPay' => $toPay,
            'ready' => $ready,
            'served' => $served
        ]);
    }

    public function markAsPaid($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->payment_status = 'paid';
        $order->status = 'processing'; // Send to kitchen for processing
        $order->save();

        session()->flash('success', 'Objednávka #' . $order->id . ' označená ako zaplatená a poslaná do kuchyne.');
    }

    public function markAsServed($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->waiter_status = 'served';
        $order->save();

        session()->flash('success', 'Objednávka #' . $order->id . ' označená ako podaná.');
    }
}
