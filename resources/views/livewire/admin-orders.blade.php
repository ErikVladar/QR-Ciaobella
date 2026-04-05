<div>
    <div class="mb-6 grid gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2 xl:grid-cols-5">
        <div>
            <label for="order-search" class="block text-sm font-medium text-gray-700">Číslo objednávky</label>
            <input
                id="order-search"
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Napr. 125"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-rose-500 focus:outline-none focus:ring-rose-500"
            >
        </div>

        <div>
            <label for="status-filter" class="block text-sm font-medium text-gray-700">Status</label>
            <select
                id="status-filter"
                wire:model.live="statusFilter"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-rose-500 focus:outline-none focus:ring-rose-500"
            >
                <option value="">Všetky statusy</option>
                <option value="processing">Spracováva sa</option>
                <option value="completed">Dokončená</option>
                <option value="cancelled">Zrušená</option>
                <option value="cart">Košík</option>
            </select>
        </div>

        <div>
            <label for="table-filter" class="block text-sm font-medium text-gray-700">Stôl</label>
            <input
                id="table-filter"
                type="text"
                wire:model.live="tableFilter"
                placeholder="Napr. 4, A12"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-rose-500 focus:outline-none focus:ring-rose-500"
            >
        </div>

        <div>
            <label for="date-filter" class="block text-sm font-medium text-gray-700">Dátum</label>
            <input
                id="date-filter"
                type="date"
                wire:model.live="dateFilter"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm focus:border-rose-500 focus:outline-none focus:ring-rose-500"
            >
        </div>

        <div class="flex items-end">
            <button
                type="button"
                wire:click="clearFilters"
                class="w-full rounded-lg bg-gray-200 px-4 py-2 font-semibold text-gray-700 transition hover:bg-gray-300"
            >
                Vymazať filtre
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[920px]">
            <thead class="bg-gray-100 border-b-2 border-gray-200">
                <tr>
                    <th class="px-4 py-3">Číslo objednávky</th>
                    <th class="px-4 py-3">Stôl</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Detaily</th>
                    <th class="px-4 py-3">Dátum</th>
                    <th class="px-4 py-3 text-right">Suma</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 align-top">
                        <td class="px-4 py-3 font-medium text-gray-900">#{{ $order->id }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $order->table_number ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $this->statusClasses($order) }}">
                                {{ $this->statusLabel($order) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button type="button"
                                    onclick="window.openOrderDetails('admin-order-details-{{ $order->id }}')"
                                    class="inline-flex items-center gap-2 rounded-lg border border-blue-300 bg-blue-100 px-3 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-200 transition">
                                <span>📋</span>
                                <span>Detaily</span>
                            </button>

                            <dialog wire:ignore.self id="admin-order-details-{{ $order->id }}" class="w-[calc(100%-1.5rem)] sm:w-full max-w-2xl rounded-2xl p-0 backdrop:bg-black/50">
                                <div class="p-5 sm:p-6">
                                    <div class="mb-4 flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">Detaily objednávky #{{ $order->id }}</h3>
                                            <p class="text-sm text-gray-600">
                                                Číslo objednávky: #{{ $order->id }}
                                                • Stôl: {{ $order->table_number ?? '-' }}
                                                • {{ $order->created_at->format('d.m.Y H:i') }}
                                            </p>
                                        </div>
                                        <button type="button" onclick="window.closeOrderDetails(this)" class="text-gray-400 hover:text-gray-700 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="mb-4 grid gap-3 rounded-xl bg-gray-50 p-4 text-sm text-gray-700 sm:grid-cols-2">
                                        <div><span class="font-semibold text-gray-900">Status:</span> {{ $this->statusLabel($order) }}</div>
                                        <div><span class="font-semibold text-gray-900">Platba:</span> {{ $order->payment_method === 'card' ? 'Kartou' : 'Pri pulte' }}</div>
                                        <div><span class="font-semibold text-gray-900">Úhrada:</span> {{ $order->payment_status === 'paid' ? 'Zaplatené' : 'Nezaplatené' }}</div>
                                        <div><span class="font-semibold text-gray-900">Suma:</span> {{ number_format($order->total, 2) }}€</div>
                                    </div>

                                    <div class="max-h-[65vh] space-y-3 overflow-y-auto pr-1">
                                        @foreach ($order->items as $item)
                                            <div class="rounded-xl border border-gray-200 bg-white p-3">
                                                <div class="mb-1 flex items-start justify-between">
                                                    <div class="font-bold text-gray-900">
                                                        {{ $item->product->name ?? $item->product_name ?? ('Product #' . $item->product_id) }}
                                                    </div>
                                                    <div class="font-bold text-gray-700">×{{ $item->quantity }}</div>
                                                </div>
                                                <div class="text-sm text-gray-600">{{ number_format($item->price, 2) }}€ za kus</div>

                                                @if($item->additions && $item->additions->count())
                                                    <div class="mt-2 rounded border-l-2 border-green-400 bg-green-50 py-2 pl-3 pr-2">
                                                        <div class="mb-1 text-xs font-bold text-green-800">✓ PRÍLOHY:</div>
                                                        <ul class="space-y-1">
                                                            @foreach($item->additions as $add)
                                                                <li class="text-sm font-medium text-green-900">• {{ $add->addition_name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </dialog>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900 whitespace-nowrap">{{ number_format($order->total, 2) }}€</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Žiadne objednávky nevyhovujú zvoleným filtrom.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
        <div class="mt-6 flex items-center justify-between gap-4">
            <p class="text-sm text-gray-600">
                Zobrazených {{ $orders->firstItem() }}–{{ $orders->lastItem() }} z {{ $orders->total() }} objednávok
            </p>

            <div>
                {{ $orders->links() }}
            </div>
        </div>
    @endif

    <script>
        window.openOrderDetails = function (dialogId) {
            const modal = document.getElementById(dialogId);
            if (modal) {
                modal.showModal();
            }
        };

        window.closeOrderDetails = function (button) {
            const modal = button.closest('dialog');
            if (modal) {
                modal.close();
            }
        };
    </script>
</div>