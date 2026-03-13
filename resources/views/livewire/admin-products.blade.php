<div>
    @if(session('notify'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('notify') }}
        </div>
    @endif

    <button wire:click="openCreateForm" class="mb-6 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        + Nový produkt
    </button>

    @if($showForm)
        <div class="mb-6 p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-lg font-bold mb-4">{{ $editingId ? 'Upraviť produkt' : 'Nový produkt' }}</h3>

            <form wire:submit="saveProduct" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Názov *</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Cena (EUR) *</label>
                    <input type="number" wire:model="price" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                    @error('price') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategória *</label>
                    <select wire:model="category_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" required>
                        <option value="">-- Vyberte kategóriu --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Popis</label>
                    <textarea wire:model="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 h-20"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Fotografia</label>
                    <input type="file" wire:model="image" accept="image/*" class="mt-1 block w-full">
                    @error('image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        {{ $editingId ? 'Aktualizovať' : 'Vytvoriť' }}
                    </button>
                    <button type="button" wire:click="resetForm" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                        Zrušiť
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-100 border-b-2 border-gray-200">
                <tr>
                    <th class="px-4 py-2">Fotografia</th>
                    <th class="px-4 py-2">Názov</th>
                    <th class="px-4 py-2">Kategória</th>
                    <th class="px-4 py-2">Cena</th>
                    <th class="px-4 py-2">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-2">
                            @if($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover rounded">
                            @else
                                <div class="h-10 w-10 bg-gray-200 rounded"></div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <strong>{{ $product->name }}</strong>
                            @if($product->description)
                                <div class="text-xs text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm">{{ $product->category->name }}</td>
                        <td class="px-4 py-2 font-medium">{{ number_format($product->price, 2) }} €</td>
                        <td class="px-4 py-2 text-sm">
                            <button wire:click="openEditForm({{ $product->id }})" class="text-yellow-600 hover:text-yellow-800 font-medium">Upraviť</button>
                            <button wire:click="deleteProduct({{ $product->id }})" onclick="return confirm('Naozaj?')" class="text-red-600 hover:text-red-800 font-medium ml-2">Vymazať</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Žiadne produkty. Vytvorte prvý!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
