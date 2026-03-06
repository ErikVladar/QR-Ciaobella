<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class KitchenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // List recent orders for kitchen
    public function index()
    {
        $user = auth()->user();
        abort_unless($user && $user->isKitchen(), 403);

        return view('kitchen.index');
    }

    // Update order status (processing, completed, cancelled)
    public function updateStatus(Request $request, Order $order)
    {
        $user = auth()->user();
        abort_unless($user && $user->isKitchen(), 403);

        $request->validate([
            'status' => 'required|string|in:processing,completed,cancelled',
        ]);

        $newStatus = $request->input('status');

        $order->status = $newStatus;

        if ($order->save()) {
            // reload to ensure fresh data
            $order->refresh();
            Log::info('Kitchen status updated', ['order_id' => $order->id, 'status' => $newStatus, 'by' => $user->id]);
            return redirect()->route('kitchen.index')->with('success', 'Order #' . $order->id . ' updated to ' . $newStatus . '.');
        }

        Log::warning('Failed to save kitchen status', ['order_id' => $order->id, 'status' => $newStatus]);
        return redirect()->route('kitchen.index')->with('error', 'Failed to update order status.');
    }

    // API endpoint for real-time order polling
    public function getOrders()
    {
        $user = auth()->user();
        abort_unless($user && $user->isKitchen(), 403);

        $orders = Order::with('items.product', 'items.additions')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'total' => $order->total,
                    'status' => $order->status,
                    'table_number' => $order->table_number,
                    'created_at' => $order->created_at->format('H:i'),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'product_name' => $item->product->name ?? $item->product_name ?? ('Product #' . $item->product_id),
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'additions' => $item->additions->map(function ($add) {
                                return [
                                    'name' => $add->addition_name,
                                    'price' => $add->addition_price,
                                ];
                            }),
                        ];
                    }),
                ];
            }),
            'count' => $orders->count(),
        ]);
    }
}
