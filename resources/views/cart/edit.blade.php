<x-app-layout>
    <!-- Header -->
    <div class="bg-white border-b sticky top-0 z-10 shadow-sm">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('cart.view') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Upraviť doplnky</h1>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Product Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
            <p class="text-xl font-semibold text-green-600">{{ number_format($product->price, 2) }}€</p>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('cart.updateAdditions', $id) }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
                <label class="block text-lg font-bold text-gray-900 mb-4">
                    🍕 Vyberte doplnky (maximálne 4)
                </label>
                
                <div class="space-y-2">
                    @foreach($additions as $addition)
                        <label class="flex items-center gap-3 cursor-pointer hover:bg-gray-50 p-3 rounded-xl transition border-2
                            {{ in_array($addition->id, $item['addition_ids'] ?? []) ? 'border-green-500 bg-green-50' : 'border-transparent' }}">
                            <input type="checkbox" 
                                   name="additions[]" 
                                   value="{{ $addition->id }}" 
                                   class="w-6 h-6 rounded border-gray-300 text-green-600 focus:ring-green-500 addition-checkbox"
                                   {{ in_array($addition->id, $item['addition_ids'] ?? []) ? 'checked' : '' }}>
                            <span class="flex-1 font-medium text-gray-900">{{ $addition->name }}</span>
                            <span class="text-lg font-bold text-green-600">+{{ number_format($addition->price, 2) }}€</span>
                        </label>
                    @endforeach
                </div>
                
                <div id="limit-warning" class="hidden mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-800">
                    ⚠️ Môžete vybrať maximálne 4 doplnky
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" 
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-xl transition-colors shadow-lg text-lg">
                    Uložiť zmeny
                </button>
                <a href="{{ route('cart.view') }}" 
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-4 px-6 rounded-xl transition-colors text-center text-lg">
                    Zrušiť
                </a>
            </div>
        </form>
    </div>

    <script>
        // Limit checkboxes to 4 selections with better UX
        (function () {
            const checkboxes = document.querySelectorAll('.addition-checkbox');
            const warning = document.getElementById('limit-warning');
            
            function enforceLimit() {
                const checked = Array.from(checkboxes).filter(c => c.checked);
                
                if (checked.length >= 4) {
                    // Disable unchecked boxes
                    checkboxes.forEach(c => {
                        if (!c.checked) {
                            c.disabled = true;
                            c.closest('label').classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    });
                    warning.classList.remove('hidden');
                } else {
                    // Enable all boxes
                    checkboxes.forEach(c => {
                        c.disabled = false;
                        c.closest('label').classList.remove('opacity-50', 'cursor-not-allowed');
                    });
                    warning.classList.add('hidden');
                }
            }
            
            checkboxes.forEach(c => c.addEventListener('change', enforceLimit));
            document.addEventListener('DOMContentLoaded', enforceLimit);
        })();
    </script>
</x-app-layout>
