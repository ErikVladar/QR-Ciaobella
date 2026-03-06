<div wire:poll.5s>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
            Podané objednávky ({{ $served->count() }})
        </h2>
        
        <!-- Table Filter -->
        <div class="flex items-center gap-2">
            <label for="table-filter" class="text-sm font-semibold text-gray-700">Filter stôl:</label>
            <input 
                type="text" 
                id="table-filter"
                wire:model.live="tableFilter"
                placeholder="Číslo stola..."
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-32"
            />
        </div>
    </div>

    @if ($served->isEmpty())
        <div class="text-center py-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
            <p class="text-gray-600">Žiadne podané objednávky</p>
        </div>
    @else
        <div class="flex gap-4 overflow-x-auto pb-4">
            @foreach ($served as $order)
                <div wire:key="served-{{ $order->id }}" class="flex-shrink-0 w-[500px] rounded-2xl shadow-md overflow-hidden border-4 border-green-400 bg-green-50">
                    
                    <!-- Order Header -->
                    <div class="p-4 border-b bg-green-100/50">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="text-2xl font-bold text-gray-900">Objednávka #{{ $order->id }}</div>
                                <div class="text-sm text-gray-600">{{ $order->created_at->format('H:i') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-green-600">{{ number_format($order->total, 2) }}€</div>
                            </div>
                        </div>
                        
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                            <span class="w-2 h-2 rounded-full bg-green-600"></span>
                            PODANÉ
                        </div>
                    </div>

                    <!-- Payment Method & Table -->
                    <div class="px-4 py-3 bg-gray-100 border-b">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Stôl:</span>
                                <span class="text-2xl font-bold text-blue-600">{{ $order->table_number ?? '-' }}</span>
                            </div>
                            <div class="text-sm font-semibold text-gray-700 bg-white px-3 py-1 rounded-full border">
                                {{ $order->payment_method === 'card' ? '💳 Karta' : '🧾 Pri pulte' }}
                                <span class="ml-1 text-green-600">✓</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="p-4">
                        <div class="space-y-3">
                            @foreach ($order->items as $item)
                                <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start mb-1">
                                        <div class="font-bold text-gray-900">
                                            {{ $item->product->name ?? $item->product_name ?? ('Product #' . $item->product_id) }}
                                        </div>
                                        <div class="text-lg font-bold text-gray-700">×{{ $item->quantity }}</div>
                                    </div>
                                    <div class="text-sm text-gray-600">{{ number_format($item->price, 2) }}€ za kus</div>
                                    
                                    @if($item->additions && $item->additions->count())
                                        <div class="mt-2 pl-3 border-l-2 border-green-400 bg-green-50/50 py-2 pr-2 rounded">
                                            <div class="text-xs font-bold text-green-800 mb-1">✓ DOPLNKY:</div>
                                            <ul class="space-y-1">
                                                @foreach($item->additions as $add)
                                                    <li class="text-sm text-green-900 font-medium">• {{ $add->addition_name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
