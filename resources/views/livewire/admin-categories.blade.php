<div>
    @if(session('notify'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('notify') }}
        </div>
    @endif

    <button wire:click="openCreateForm" class="mb-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        + Nová kategória
    </button>

    @if($showForm)
        <div class="mb-6 p-6 bg-white rounded-lg border border-gray-200">
            <h3 class="text-lg font-bold mb-4">{{ $editingId ? 'Upraviť kategóriu' : 'Nová kategória' }}</h3>

            <form wire:submit="saveCategory" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Názov *</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Fotografia</label>
                    <input type="file" wire:model="image" accept="image/*" class="mt-1 block w-full">
                    @error('image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prílohy pre kategóriu</label>
                    <button type="button"
                            wire:click="$toggle('hasPrilohy')"
                            aria-label="Prepnúť prílohy"
                            aria-pressed="{{ $hasPrilohy ? 'true' : 'false' }}"
                            class="inline-flex h-8 w-12 items-center rounded-full p-1 transition-colors duration-200 {{ $hasPrilohy ? 'bg-green-600 justify-end' : 'bg-red-600 justify-start' }}">
                        <span class="block h-6 w-6 rounded-full bg-white shadow-sm transition-all duration-200"></span>
                    </button>
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                @if($category->image_path)
                    <img src="{{ Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-40 object-cover">
                @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400">
                        Bez fotografie
                    </div>
                @endif

                <div class="p-4">
                    <h4 class="font-bold text-lg text-gray-900">{{ $category->name }}</h4>

                    <div class="mt-2 text-sm text-gray-600">
                        @if($category->has_prilohy)
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">Má prílohy</span>
                        @else
                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-medium">Bez prílohy</span>
                        @endif
                    </div>

                    <div class="mt-4 flex gap-2">
                        <button wire:click="openEditForm({{ $category->id }})" class="flex-1 px-3 py-2 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                            Upraviť
                        </button>
                        <button wire:click="deleteCategory({{ $category->id }})" onclick="return confirm('Naozaj chcete vymazať túto kategóriu?')" class="flex-1 px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                            Vymazať
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                Žiadne kategórie. Vytvorte prvú!
            </div>
        @endforelse
    </div>
</div>
