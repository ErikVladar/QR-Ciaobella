<x-app-layout>
<div class="min-h-screen bg-gray-50">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Spravovanie príloh</h2>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">← Späť</a>
                    </div>

                    @livewire('admin-additions')
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
