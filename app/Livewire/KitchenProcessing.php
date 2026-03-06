<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class KitchenProcessing extends Component
{
    public function render()
    {
        $processingOrders = Order::where('payment_status', 'paid')
            ->where('status', 'processing')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.kitchen-processing', [
            'processingOrders' => $processingOrders,
        ]);
    }

    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::find($orderId);
        
        if ($order && $order->payment_status === 'paid') {
            $order->update([
                'status' => $newStatus,
            ]);

            if ($newStatus === 'completed') {
                $order->update(['waiter_status' => 'ready']);
            }

            session()->flash('success', 'Stav objednávky bol aktualizovaný!');
        }
    }
}
