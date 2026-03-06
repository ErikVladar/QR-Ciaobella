<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>New Order Received</h2>
    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>User ID:</strong> {{ $order->user_id ?? 'Guest' }}</p>
    <p><strong>Total:</strong> {{ number_format($order->total, 2) }}€</p>

    <h3>Items:</h3>
    <ul>
        @foreach ($order->items as $item)
            <li>
                {{ $item->product->name ?? $item->product_name ?? ('Product #' . $item->product_id) }} — {{ $item->quantity }} × {{ number_format($item->price, 2) }}€
                @if($item->additions && $item->additions->count() > 0)
                    <ul style="margin-top: 4px; margin-left: 20px;">
                        @foreach($item->additions as $addition)
                            <li style="font-size: 0.9em; color: #666;">{{ $addition->addition_name }} (+{{ number_format($addition->addition_price, 2) }}€)</li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>

    <p><em>This order is now being processed.</em></p>
</body>
</html>
