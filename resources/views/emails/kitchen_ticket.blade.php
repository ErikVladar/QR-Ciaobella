<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kuchynný lístok — Objednávka #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; }
        .ticket { max-width: 600px; margin: 0 auto; padding: 12px; border: 1px solid #ddd; }
        .header { display:flex; justify-content:space-between; align-items:center; }
        .items { margin-top:12px; }
        .item { padding:8px 0; border-bottom:1px dashed #eee; }
        .item-title { font-weight:700; }
        .additions { margin-left:14px; color:#444; font-size:0.95em; }
        .meta { margin-top:12px; font-size:0.95em; color:#333; }
        .total { font-weight:700; margin-top:8px; }
        .print-note { margin-top:10px; font-size:0.85em; color:#666; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <div>
                <h2 style="margin:0;">Kuchynný lístok</h2>
                <div style="font-size:0.95em; color:#555;">Objednávka #{{ $order->id }} • {{ $order->created_at->format('Y-m-d H:i') }}</div>
            </div>
            <div style="text-align:right">
                <div style="font-weight:700; font-size:1.1em;">Celkom: {{ number_format($order->total,2) }}€</div>
                <div style="font-size:0.9em; color:#666;">Stav: {{ $order->status }}</div>
            </div>
        </div>

        <div class="meta">
            <div><strong>Používateľ:</strong> {{ $order->user_id ?? 'Hosť' }}</div>
            <div><strong>Stôl:</strong> {{ $order->table_number ?? '-' }}</div>
        </div>

        <div class="items">
            @foreach($order->items as $item)
                <div class="item">
                    <div class="item-title">{{ $item->product->name ?? $item->product_name ?? ('Product #' . $item->product_id) }} &times; {{ $item->quantity }}</div>
                    <div>{{ number_format($item->price,2) }}€ za kus — Suma: {{ number_format($item->price * $item->quantity,2) }}€</div>

                    @if($item->additions && $item->additions->count())
                        <div class="additions">
                            <div style="font-weight:600; margin-bottom:4px;">Vrátane doplnkov:</div>
                            <ul style="margin:0; padding-left:18px;">
                                @foreach($item->additions as $add)
                                    <li>{{ $add->addition_name }} ({{ number_format($add->addition_price,2) }}€)</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="total">Celková suma objednávky: {{ number_format($order->total,2) }}€</div>

        <div class="print-note">Vytlačte tento lístok a umiestnite ho do kuchyne. Dodržujte doplnky na jednotlivé položky vyššie.</div>
    </div>
</body>
</html>
