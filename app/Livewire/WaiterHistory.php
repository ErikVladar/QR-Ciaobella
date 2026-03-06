<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class WaiterHistory extends Component
{
    public $tableFilter = '';

    public function render()
    {
        $query = Order::where('waiter_status', 'served');

        if ($this->tableFilter !== '') {
            $query->where('table_number', $this->tableFilter);
        }

        $served = $query->orderBy('created_at', 'desc')->get();

        return view('livewire.waiter-history', [
            'served' => $served,
        ]);
    }
}
