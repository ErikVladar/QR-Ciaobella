<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class WaiterActive extends Component
{
    public function render()
    {
        $toPay = Order::where('payment_status', 'unpaid')
            ->where('payment_method', 'counter')
            ->orderBy('created_at', 'desc')
            ->get();

        $ready = Order::where('payment_status', 'paid')
            ->where('waiter_status', 'ready')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.waiter-active', [
            'toPay' => $toPay,
            'ready' => $ready,
        ]);
    }

    public function markAsPaid($orderId)
    {
        $order = Order::find($orderId);
        
        if ($order && $order->payment_method === 'counter') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);

            session()->flash('success', 'Objednávka bola označená ako zaplatená!');
        }
    }

    public function markAsServed($orderId)
    {
        $order = Order::find($orderId);
        
        if ($order && $order->waiter_status === 'ready') {
            $order->update([
                'waiter_status' => 'served',
            ]);

            session()->flash('success', 'Objednávka bola označená ako podaná!');
        }
    }
}
