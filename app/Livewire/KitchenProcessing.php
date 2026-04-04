<?php

namespace App\Livewire;

use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;

class KitchenProcessing extends Component
{
    public function render()
    {
        $timezone = config('app.timezone', 'Europe/Bratislava');
        $today = Carbon::now($timezone)->toDateString();

        // Show all processing orders (including unpaid counter orders)
        $processingOrders = Order::where('status', 'processing')
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.kitchen-processing', [
            'processingOrders' => $processingOrders,
        ]);
    }

    public function updateStatus($orderId, $newStatus)
    {
        $order = Order::find($orderId);
        
        if ($order) {
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
