<x-app-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-br text-black">
        {{-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-2 sm:mb-3">Vitajte v našej reštaurácii</h1>
            <p class="text-lg sm:text-xl text-gray-800 mb-4">Objednajte si jedlo rýchlo a pohodlne</p>
        </div> --}}
    </div>

    <!-- Categories Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-4 lg:px-8 py-6 sm:py-10">
        <h2 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-gray-900">Vyberte kategóriu</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach($categories as $category)
                <a href="{{ route('categories.products', $category) }}" 
                   class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="aspect-w-16 aspect-h-10 relative overflow-hidden">
                        <img src="{{ asset($category->image_path) }}" 
                             alt="{{ $category->name }}" 
                             class="w-full h-48 sm:h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    </div>
                    <div class="p-4 sm:p-5">
                        <h3 class="font-bold text-lg sm:text-xl text-gray-900 group-hover:text-green-600 transition-colors">
                            {{ $category->name }}
                        </h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
