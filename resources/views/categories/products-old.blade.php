<x-app-layout>
    <!-- Header -->
    <div class="bg-white border-b sticky top-0 z-10 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative">
                        <img src="{{ asset($category->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-40 sm:h-48 object-cover">
                    </div>
                    
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="p-4">
                        @csrf
                        <h2 class="font-bold text-lg text-gray-900 mb-1">{{ $product->name }}</h2>
                        <p class="text-2xl font-bold text-green-600 mb-3">{{ number_format($product->price, 2) }}€</p>
                        
                        @if($category->name === 'Pizza')
                            <div class="mb-4 p-3 bg-gray-50 rounded-xl border border-gray-200">
                                <label class="block text-sm font-bold text-gray-700 mb-2">🍕 Doplnky (max 4)</label>
                                @php
                                    $additions = \App\Models\PizzaAddition::all();
                                @endphp
                                <div class="space-y-2">
                                    @foreach($additions as $addition)
                                        <label class="flex items-center gap-2 cursor-pointer hover:bg-white p-2 rounded-lg transition">
                                            <input type="checkbox" name="additions[]" value="{{ $addition->id }}" 
                                                   class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            <span class="text-sm text-gray-700 flex-1">{{ $addition->name }}</span>
                                            <span class="text-sm font-semibold text-green-600">+{{ number_format($addition->price, 2) }}€</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                            Pridať do košíka
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
