<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potvrdenie objednávky</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Ďakujeme za objednávku!</h1>
                <p class="text-lg text-gray-600">Vaša objednávka bola úspešne prijatá</p>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <!-- Order Header -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Číslo objednávky</p>
                            <p class="text-3xl font-bold">#{{ $order->id }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-green-100 text-sm font-medium mb-1">Stôl</p>
                            <p class="text-3xl font-bold">{{ $order->table_number }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-green-400">
                        <p class="text-green-100 text-sm">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="px-6 py-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Položky objednávky</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-900">
                                            {{ $item->product->name ?? $item->product_name ?? ('Produkt #' . $item->product_id) }}
                                        </p>
                                        <p class="text-sm text-gray-600">{{ number_format($item->price, 2) }}€ × {{ $item->quantity }}</p>
                                    </div>
                                    <p class="font-bold text-gray-900">{{ number_format($item->price * $item->quantity, 2) }}€</p>
                                </div>
                                
                                @if($item->additions && $item->additions->count() > 0)
                                    <div class="ml-4 pl-3 border-l-2 border-green-400 bg-green-50 py-2 pr-2 rounded">
                                        <p class="text-xs font-bold text-green-800 mb-1">✓ Doplnky:</p>
                                        <ul class="space-y-1">
                                            @foreach($item->additions as $addition)
                                                <li class="text-sm text-green-900 flex justify-between">
                                                    <span>• {{ $addition->addition_name }}</span>
                                                    <span class="font-medium">+{{ number_format($addition->addition_price, 2) }}€</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total -->
                <div class="bg-gray-50 px-6 py-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-900">Celková suma</span>
                        <span class="text-3xl font-bold text-green-600">{{ number_format($order->total, 2) }}€</span>
                    </div>
                </div>
            </div>

            <!-- Status Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl px-6 py-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-900 mb-1">Status: Spracováva sa</p>
                        <p class="text-sm text-blue-800">Vaša objednávka je práve v príprave. Jedlo bude čoskoro doručené na váš stôl.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('categories.index') }}" 
                   class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-xl text-center transition-colors">
                    Späť na menu
                </a>
                {{-- <button onclick="window.print()" 
                        class="flex-1 bg-white hover:bg-gray-50 text-gray-900 font-bold py-4 px-6 rounded-xl border-2 border-gray-300 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Vytlačiť účtenku
                </button> --}}
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .max-w-2xl, .max-w-2xl * {
                visibility: visible;
            }
            .max-w-2xl {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            nav, footer, button, .bg-blue-50 {
                display: none !important;
            }
        }
    </style>
</body>
</html>
