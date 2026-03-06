<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PizzaAddition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;



class CartController extends Controller
{
    // Add product to cart
    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);

        // Get selected additions (if pizza)
        $additionIds = $request->input('additions', []);
        $additionIds = array_slice($additionIds, 0, 4); // limit to 4

        // Fetch addition details
        $additions = collect();
        $additionPrice = 0;
        if (!empty($additionIds)) {
            $additions = \App\Models\PizzaAddition::whereIn('id', $additionIds)->get();
            $additionPrice = $additions->sum('price');
        }

        $cartKey = $product->id . '_' . md5(json_encode($additionIds)); // unique key per product + addition combo

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'addition_ids' => $additionIds,
                'addition_names' => $additions->pluck('name')->toArray(),
                'addition_price' => $additionPrice,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.view');
    }

    // View cart
    public function view()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(function($item) {
            $itemPrice = ($item['price'] ?? 0) + ($item['addition_price'] ?? 0);
            return $itemPrice * ($item['quantity'] ?? 1);
        });
        return view('cart.view', compact('cart', 'total'));
    }

    // Show edit additions form for a cart item
    public function edit($id)
    {
        $cart = session()->get('cart', []);
        if (! isset($cart[$id])) {
            abort(404);
        }

        $item = $cart[$id];
        $product = Product::find($item['product_id']);
        $additions = PizzaAddition::all();

        return view('cart.edit', compact('id', 'item', 'product', 'additions'));
    }

    // Update additions for a cart item
    public function updateAdditions(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (! isset($cart[$id])) {
            return redirect()->route('cart.view')->with('error', 'Cart item not found.');
        }

        $additionIds = $request->input('additions', []);
        $additionIds = array_slice($additionIds, 0, 4);

        $additions = collect();
        $additionPrice = 0;
        if (! empty($additionIds)) {
            $additions = PizzaAddition::whereIn('id', $additionIds)->get();
            $additionPrice = $additions->sum('price');
        }

        // update cart entry while preserving quantity and other fields
        $cart[$id]['addition_ids'] = $additionIds;
        $cart[$id]['addition_names'] = $additions->pluck('name')->toArray();
        $cart[$id]['addition_price'] = $additionPrice;

        session()->put('cart', $cart);

        return redirect()->route('cart.view')->with('success', 'Cart item updated.');
    }

    // Update quantity
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        $quantity = (int) $request->input('quantity');

        if ($quantity <= 0) {
            // Remove item from cart
            unset($cart[$id]);
        } else {
            // Update quantity
            $cart[$id]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->back();
    }


    // Checkout (clear cart, optionally persist)
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Require table number for processing orders
        $request->validate([
            'table_number' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:card,counter'],
        ]);

        // Calculate total
        $total = collect($cart)->sum(function ($item) {
            $itemPrice = ($item['price'] ?? 0) + ($item['addition_price'] ?? 0);
            return $itemPrice * ($item['quantity'] ?? 1);
        });

        $paymentMethod = $request->input('payment_method');
        $paymentStatus = $paymentMethod === 'card' ? 'paid' : 'unpaid';
        $waiterStatus = $paymentMethod === 'card' ? 'to_pay' : 'to_pay';

        // Create order
        $order = \App\Models\Order::create([
            'user_id' => Auth::check() ? Auth::id() : null, // null if guest
            'total' => $total,
            'status' => 'processing',
            'table_number' => $request->input('table_number'),
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'waiter_status' => $waiterStatus,
        ]);

        // Save order items with additions
        foreach ($cart as $cartKey => $item) {
            $orderItem = $order->items()->create([
                'product_id' => $item['product_id'],
                'product_name' => $item['name'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Save additions for this order item
            if (!empty($item['addition_ids'])) {
                $additions = \App\Models\PizzaAddition::whereIn('id', $item['addition_ids'])->get();
                foreach ($additions as $addition) {
                    $orderItem->additions()->create([
                        'pizza_addition_id' => $addition->id,
                        'addition_name' => $addition->name,
                        'addition_price' => $addition->price,
                    ]);
                }
            }
        }

        // Eager-load the product relation for the email view
        $order->load('items.product', 'items.additions');

        // Queue the order placed mail (requires queue worker to run in production)
        try {
            Mail::to('erikvladar@gmail.com')->queue(new OrderPlacedMail($order));
        } catch (\Throwable $e) {
            // Fallback to send if queue is not configured or fails
            Mail::to('erikvladar@gmail.com')->send(new OrderPlacedMail($order));
        }

        // Clear cart
        session()->forget('cart');

        // Redirect to confirmation page
        return redirect()->route('order.confirmation', $order->id);
    }

    // Show order confirmation page
    public function confirmation($orderId)
    {
        $order = \App\Models\Order::with('items.product', 'items.additions')->findOrFail($orderId);
        return view('cart.confirmation', compact('order'));
    }
}
