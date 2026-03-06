<x-app-layout>
    <!-- Success Toast -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
            class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-xl shadow-xl z-50 flex items-center gap-2 max-w-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-32">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Váš košík</h1>
            <a href="{{ route('categories.index') }}" class="text-green-600 hover:text-green-700 font-medium text-sm sm:text-base">
                ← Pokračovať v nákupe
            </a>
        </div>

        @if ($cart)
            <!-- Cart Items -->
            <div class="space-y-4 mb-6">
                @foreach ($cart as $id => $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5">
                        <div class="flex gap-4">
                            <!-- Item Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $item['name'] ?? 'Product' }}</h3>
                                
                                @if(!empty($item['addition_names']))
                                    <div class="flex flex-wrap gap-1 mb-2">
                                        @foreach($item['addition_names'] as $addName)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $addName }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <p class="text-xl font-bold text-green-600 mb-3">
                                    {{ number_format(($item['price'] ?? 0) + ($item['addition_price'] ?? 0), 2) }}€
                                    <span class="text-sm text-gray-500 font-normal">× {{ $item['quantity'] ?? 1 }}</span>
                                </p>
                                
                                <!-- Quantity Control -->
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="quantity-form inline-flex items-center gap-2">
                                    @csrf
                                    <label class="text-sm font-medium text-gray-700">Množstvo:</label>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] ?? 1 }}" min="0"
                                        class="quantity-input w-20 px-3 py-2 border border-gray-300 rounded-lg text-center font-semibold focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        data-id="{{ $id }}">
                                </form>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('cart.edit', $id) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('cart.update', $id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="0">
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Item Total -->
                        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-sm text-gray-600">Celková cena položky:</span>
                            <span class="text-lg font-bold text-gray-900">
                                {{ number_format((($item['price'] ?? 0) + ($item['addition_price'] ?? 0)) * ($item['quantity'] ?? 1), 2) }}€
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sticky Checkout Bar -->
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-2xl z-20">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <div class="flex flex-col gap-3">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <!-- Table Number -->
                                <div class="flex-1">
                                    <label for="table_number" class="block text-sm font-bold text-gray-700 mb-1">
                                        Číslo stola <span class="text-red-500">*</span>
                                    </label>
                                    <input id="table_number" name="table_number" type="number" min="1" required
                                        value="{{ old('table_number') }}"
                                        placeholder="Zadajte číslo stola"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl font-semibold text-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    @error('table_number')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div class="flex-1">
                                    <label class="block text-sm font-bold text-gray-700 mb-1">
                                        Spôsob platby <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex gap-2">
                                        <label class="flex-1 relative">
                                            <input type="radio" name="payment_method" value="card" required
                                                class="peer sr-only">
                                            <div class="px-4 py-3 border-2 border-gray-300 rounded-xl cursor-pointer text-center font-semibold transition-all peer-checked:border-green-600 peer-checked:bg-green-50 peer-checked:text-green-700">
                                                💳 Karta
                                            </div>
                                        </label>
                                        <label class="flex-1 relative">
                                            <input type="radio" name="payment_method" value="counter" required
                                                class="peer sr-only">
                                            <div class="px-4 py-3 border-2 border-gray-300 rounded-xl cursor-pointer text-center font-semibold transition-all peer-checked:border-green-600 peer-checked:bg-green-50 peer-checked:text-green-700">
                                                🧾 Pri pulte
                                            </div>
                                        </label>
                                    </div>
                                    @error('payment_method')
                                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Total & Button -->
                            <div class="flex items-end gap-3">
                                <div class="flex-1">
                                    <div class="text-sm text-gray-600 mb-1">Celková suma</div>
                                    <div class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($total, 2) }}€</div>
                                </div>
                                <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 sm:px-8 rounded-xl transition-colors shadow-lg text-lg whitespace-nowrap">
                                    Odoslať objednávku
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <svg class="mx-auto h-24 w-24 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Váš košík je prázdny</h2>
                <p class="text-gray-600 mb-6">Pridajte si produkty z našej ponuky</p>
                <a href="{{ route('categories.index') }}" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors">
                    Prejsť na kategórie
                </a>
            </div>
        @endif
    </div>

    <script>
        // Submit quantity forms on input change
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', () => {
                const form = input.closest('.quantity-form');
                form.submit();
            });
        });
    </script>
</x-app-layout>
