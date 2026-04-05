<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class AdminOrders extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public string $search = '';
    public string $statusFilter = '';
    public string $tableFilter = '';
    public string $dateFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTableFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFilter(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->tableFilter = '';
        $this->dateFilter = '';
        $this->resetPage();
    }

    public function statusLabel(Order $order): string
    {
        return match ($order->status) {
            'processing' => 'Spracováva sa',
            'completed' => 'Dokončená',
            'cancelled' => 'Zrušená',
            'cart' => 'Košík',
            default => ucfirst($order->status),
        };
    }

    public function statusClasses(Order $order): string
    {
        return match ($order->status) {
            'processing' => 'bg-amber-100 text-amber-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'cart' => 'bg-gray-100 text-gray-800',
            default => 'bg-blue-100 text-blue-800',
        };
    }

    public function render()
    {
        $query = Order::query()
            ->with(['user', 'items.product', 'items.additions'])
            ->latest();

        if ($this->search !== '') {
            $search = trim($this->search);

            $query->where('id', 'like', '%' . $search . '%');
        }

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->tableFilter !== '') {
            $query->where('table_number', 'like', '%' . trim($this->tableFilter) . '%');
        }

        if ($this->dateFilter !== '') {
            $query->whereDate('created_at', $this->dateFilter);
        }

        return view('livewire.admin-orders', [
            'orders' => $query->paginate(20),
        ]);
    }
}