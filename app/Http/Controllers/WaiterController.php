<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class WaiterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // List orders for waiter
    public function index()
    {
        $user = auth()->user();
        abort_unless($user && $user->isWaiter(), 403);

        return view('waiter.index');
    }

    // Mark order as paid (for counter payments)
    public function markAsPaid(Request $request, Order $order)
    {
        $user = auth()->user();
        abort_unless($user && $user->isWaiter(), 403);

        $order->payment_status = 'paid';
        $order->save();

        return redirect()->back()->with('success', 'Objednávka označená ako zaplatená.');
    }

    // Mark order as served
    public function markAsServed(Request $request, Order $order)
    {
        $user = auth()->user();
        abort_unless($user && $user->isWaiter(), 403);

        $order->waiter_status = 'served';
        $order->save();

        return redirect()->back()->with('success', 'Objednávka označená ako podaná.');
    }
}
