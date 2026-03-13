<div>
    @if(session('notify'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('notify') }}
        </div>
    @endif

    <button wire:click="openCreateForm" class="mb-6 px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
        + Nový používateľ
    </button>

    @if($showForm)
        <div class="mb-6 p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-lg font-bold mb-4">{{ $editingId ? 'Upraviť používateľa' : 'Nový používateľ' }}</h3>

            <form wire:submit="saveUser" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Meno *</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" wire:model="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Heslo {{ $editingId ? '(necháte prázdne, ak nechcete zmeniť)' : '*' }}</label>
                    <input type="password" wire:model="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" {{ !$editingId ? 'required' : '' }}>
                    @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Rola *</label>
                    <select wire:model="role" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500" required>
                        @foreach($roles as $roleOption)
                            <option value="{{ $roleOption }}">
                                @switch($roleOption)
                                    @case('admin')
                                        Administrátor
                                        @break
                                    @case('kitchen')
                                        Kuchyňa
                                        @break
                                    @case('waiter')
                                        Čašník
                                        @break
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                    @error('role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
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
                    <th class="px-4 py-2">Meno</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Rola</th>
                    <th class="px-4 py-2">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-2">
                            <span class="
                                inline-block px-3 py-1 rounded text-xs font-medium
                                @switch($user->role)
                                    @case('admin')
                                        bg-purple-100 text-purple-800
                                        @break
                                    @case('kitchen')
                                        bg-orange-100 text-orange-800
                                        @break
                                    @case('waiter')
                                        bg-blue-100 text-blue-800
                                        @break
                                @endswitch
                            ">
                                @switch($user->role)
                                    @case('admin')
                                        Administrátor
                                        @break
                                    @case('kitchen')
                                        Kuchyňa
                                        @break
                                    @case('waiter')
                                        Čašník
                                        @break
                                @endswitch
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm">
                            <button wire:click="openEditForm({{ $user->id }})" class="text-yellow-600 hover:text-yellow-800 font-medium">Upraviť</button>
                            @if($user->id !== auth()->id())
                                <button wire:click="deleteUser({{ $user->id }})" onclick="return confirm('Naozaj chcete vymazať tohto používateľa?')" class="text-red-600 hover:text-red-800 font-medium ml-2">Vymazať</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">Žiadni používatelia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
