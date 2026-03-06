<x-app-layout>
    <!-- Header -->
    <div class="bg-white border-b sticky top-0 z-10 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">🍳 Kuchyňa</h1>
                <div class="text-sm text-gray-600">
                    {{ $orders->count() }} {{ $orders->count() === 1 ? 'objednávka' : 'objednávok' }}
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-xl border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Zatiaľ žiadne objednávky</h2>
                <p class="text-gray-600">Nové objednávky sa objavia tu</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                @foreach ($orders as $order)
                    <div class="bg-white rounded-2xl shadow-sm border-2 
                        {{ $order->status === 'processing' ? 'border-yellow-400 bg-yellow-50/30' : '' }}
                        {{ $order->status === 'completed' ? 'border-green-400 bg-green-50/30' : '' }}
                        {{ $order->status === 'cancelled' ? 'border-red-400 bg-red-50/30' : '' }}
                        overflow-hidden">
                        
                        <!-- Order Header -->
                        <div class="p-4 border-b bg-white">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <div class="text-2xl font-bold text-gray-900">Objednávka #{{ $order->id }}</div>
                                    <div class="text-sm text-gray-600">{{ $order->created_at->format('H:i') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-green-600">{{ number_format($order->total, 2) }}€</div>
                                </div>
                            </div>
                            
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-bold
                                {{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                <span class="w-2 h-2 rounded-full 
                                    {{ $order->status === 'processing' ? 'bg-yellow-600 animate-pulse' : '' }}
                                    {{ $order->status === 'completed' ? 'bg-green-600' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-600' : '' }}"></span>
                                {{ strtoupper($order->status) }}
                            </div>
                        </div>

                        <!-- Table Number -->
                        <div class="px-4 py-3 border-b">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Stôl:</span>
                                <span class="text-2xl font-bold text-blue-600">{{ $order->table_number ?? '-' }}</span>
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
                                        <div class="text-sm text-gray-600">{{ number_format($item->price, 2) }}€ each</div>
                                        
                                        @if($item->additions && $item->additions->count())
                                            <div class="mt-2 pl-3 border-l-2 border-green-400 bg-green-50/50 py-2 pr-2 rounded">
                                                <div class="text-xs font-bold text-green-800 mb-1">✓ Prílohy:</div>
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

                        <!-- Status Update Form -->
                        <div class="p-4 border-t">
                            <div class="flex flex-col sm:flex-row gap-2">
                                <form action="{{ route('kitchen.updateStatus', $order) }}" method="POST" style="display: none;" class="status-form-processing">
                                    @csrf
                                    <input type="hidden" name="status" value="processing">
                                </form>
                                <form action="{{ route('kitchen.updateStatus', $order) }}" method="POST" style="display: none;" class="status-form-completed">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                </form>
                                <form action="{{ route('kitchen.updateStatus', $order) }}" method="POST" style="display: none;" class="status-form-cancelled">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                </form>
                                
                                <button onclick="this.parentElement.querySelector('.status-form-processing').submit()" 
                                    class="flex-1 px-4 py-2 {{ $order->status === 'processing' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-700' }} font-semibold rounded-lg transition-colors hover:bg-yellow-600 hover:text-white">
                                    ⏳ Processing
                                </button>
                                <button onclick="this.parentElement.querySelector('.status-form-completed').submit()" 
                                    class="flex-1 px-4 py-2 {{ $order->status === 'completed' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700' }} font-semibold rounded-lg transition-colors hover:bg-green-600 hover:text-white">
                                    ✅ Completed
                                </button>
                                <button onclick="this.parentElement.querySelector('.status-form-cancelled').submit()" 
                                    class="flex-1 px-4 py-2 {{ $order->status === 'cancelled' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700' }} font-semibold rounded-lg transition-colors hover:bg-red-600 hover:text-white">
                                    ❌ Cancelled
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Real-time order polling
        let isPolling = true;
        let pollInterval = 5000; // 5 seconds

        async function fetchOrders() {
            if (!isPolling) return;
            
            try {
                const response = await fetch('{{ route('kitchen.getOrders') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) throw new Error('Failed to fetch orders');
                
                const data = await response.json();
                updateOrderDisplay(data);
            } catch (error) {
                console.error('Error fetching orders:', error);
            }
        }

        function updateOrderDisplay(data) {
            const container = document.querySelector('.grid');
            const emptyState = document.querySelector('.text-center.py-16');
            const orderCount = document.querySelector('.text-sm.text-gray-600');
            
            // Update order count
            if (orderCount) {
                orderCount.textContent = `${data.count} ${data.count === 1 ? 'order' : 'orders'}`;
            }
            
            if (data.orders.length === 0) {
                if (container) container.remove();
                if (!emptyState) {
                    const mainDiv = document.querySelector('.max-w-7xl.mx-auto');
                    mainDiv.innerHTML += `
                        <div class="text-center py-16">
                            <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Zatiaľ žiadne objednávky</h2>
                            <p class="text-gray-600">Nové objednávky sa objavia tu</p>
                        </div>
                    `;
                }
                return;
            }
            
            if (emptyState) emptyState.remove();
            
            if (!container) {
                const mainDiv = document.querySelector('.max-w-7xl.mx-auto');
                mainDiv.innerHTML += '<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6"></div>';
            }
            
            const grid = document.querySelector('.grid');
            if (!grid) return;
            
            // Build HTML for all orders
            const ordersHTML = data.orders.map(order => createOrderCard(order)).join('');
            grid.innerHTML = ordersHTML;
        }

        function createOrderCard(order) {
            const statusColors = {
                processing: { 
                    cardBg: 'bg-yellow-50', 
                    border: 'border-4 border-yellow-400', 
                    badge: 'bg-yellow-100 text-yellow-800', 
                    dot: 'bg-yellow-600 animate-pulse',
                    headerBg: 'bg-yellow-100/50'
                },
                completed: { 
                    cardBg: 'bg-green-50', 
                    border: 'border-4 border-green-400', 
                    badge: 'bg-green-100 text-green-800', 
                    dot: 'bg-green-600',
                    headerBg: 'bg-green-100/50'
                },
                cancelled: { 
                    cardBg: 'bg-red-50', 
                    border: 'border-4 border-red-400', 
                    badge: 'bg-red-100 text-red-800', 
                    dot: 'bg-red-600',
                    headerBg: 'bg-red-100/50'
                }
            };
            
            const colors = statusColors[order.status] || statusColors.processing;
            
            const itemsHTML = order.items.map(item => `
                <div class="border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                    <div class="flex justify-between items-start mb-1">
                        <div class="font-bold text-gray-900">${escapeHtml(item.product_name)}</div>
                        <div class="text-lg font-bold text-gray-700">×${item.quantity}</div>
                    </div>
                    <div class="text-sm text-gray-600">${parseFloat(item.price).toFixed(2)}€ each</div>
                    ${item.additions.length > 0 ? `
                        <div class="mt-2 pl-3 border-l-2 border-green-400 bg-green-50/50 py-2 pr-2 rounded">
                            <div class="text-xs font-bold text-green-800 mb-1">✓ DOPLNKY:</div>
                            <ul class="space-y-1">
                                ${item.additions.map(add => `<li class="text-sm text-green-900 font-medium">• ${escapeHtml(add.name)}</li>`).join('')}
                            </ul>
                        </div>
                    ` : ''}
                </div>
            `).join('');
            
            return `
                <div class="${colors.cardBg} rounded-2xl shadow-md ${colors.border} overflow-hidden">
                    <div class="p-4 border-b ${colors.headerBg}">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="text-2xl font-bold text-gray-900">Order #${order.id}</div>
                                <div class="text-sm text-gray-600">${order.created_at}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-green-600">${parseFloat(order.total).toFixed(2)}€</div>
                            </div>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-bold ${colors.badge}">
                            <span class="w-2 h-2 rounded-full ${colors.dot}"></span>
                            ${order.status.toUpperCase()}
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-blue-100/50 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">Stôl:</span>
                            <span class="text-2xl font-bold text-blue-600">${order.table_number || '-'}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            ${itemsHTML}
                        </div>
                    </div>
                    <div class="p-4 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <form action="/kitchen/orders/${order.id}/status" method="POST" style="display: none;" class="status-form-processing">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="status" value="processing">
                            </form>
                            <form action="/kitchen/orders/${order.id}/status" method="POST" style="display: none;" class="status-form-completed">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="status" value="completed">
                            </form>
                            <form action="/kitchen/orders/${order.id}/status" method="POST" style="display: none;" class="status-form-cancelled">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="status" value="cancelled">
                            </form>
                            
                            <button onclick="this.parentElement.querySelector('.status-form-processing').submit()" 
                                class="flex-1 px-4 py-2 ${order.status === 'processing' ? 'bg-yellow-600 text-white' : 'bg-yellow-100 text-yellow-700'} font-semibold rounded-lg transition-colors hover:bg-yellow-600 hover:text-white">
                                ⏳ Processing
                            </button>
                            <button onclick="this.parentElement.querySelector('.status-form-completed').submit()" 
                                class="flex-1 px-4 py-2 ${order.status === 'completed' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700'} font-semibold rounded-lg transition-colors hover:bg-green-600 hover:text-white">
                                ✅ Completed
                            </button>
                            <button onclick="this.parentElement.querySelector('.status-form-cancelled').submit()" 
                                class="flex-1 px-4 py-2 ${order.status === 'cancelled' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700'} font-semibold rounded-lg transition-colors hover:bg-red-600 hover:text-white">
                                ❌ Cancelled
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Start polling
        setInterval(fetchOrders, pollInterval);

        // Stop polling when page is hidden
        document.addEventListener('visibilitychange', () => {
            isPolling = !document.hidden;
        });
    </script>
</x-app-layout>