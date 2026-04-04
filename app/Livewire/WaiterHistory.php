<?php

namespace App\Livewire;

use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;

class WaiterHistory extends Component
{
    public $tableFilter = '';

    public function render()
    {
        $timezone = config('app.timezone', 'Europe/Bratislava');
        $today = Carbon::now($timezone)->toDateString();

        $query = Order::where('waiter_status', 'served')
            ->whereDate('created_at', $today);

        if ($this->tableFilter !== '') {
            $query->where('table_number', $this->tableFilter);
        }

        $served = $query->orderBy('created_at', 'desc')->get();

        return view('livewire.waiter-history', [
            'served' => $served,
        ]);
    }
}
