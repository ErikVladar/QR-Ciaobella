<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class KitchenFinished extends Component
{
    public $tableFilter = '';

    public function render()
    {
        $query = Order::where('payment_status', 'paid')
            ->whereIn('status', ['completed', 'cancelled']);

        if ($this->tableFilter !== '') {
            $query->where('table_number', $this->tableFilter);
        }

        $finishedOrders = $query->orderBy('created_at', 'desc')->get();

        return view('livewire.kitchen-finished', [
            'finishedOrders' => $finishedOrders,
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
