<x-app-layout>
<div class="min-h-screen bg-gray-50">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-8">Administračný panel</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Categories Card -->
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white cursor-pointer hover:shadow-lg transition" onclick="window.location.href='{{ route('admin.categories') }}'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold">Kategórie</h2>
                                    <p class="text-blue-100 mt-2">Spravovať kategórie produktov</p>
                                </div>
                                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM15 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Products Card -->
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white cursor-pointer hover:shadow-lg transition" onclick="window.location.href='{{ route('admin.products') }}'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold">Produkty</h2>
                                    <p class="text-green-100 mt-2">Spravovať produkty a ceny</p>
                                </div>
                                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Users Card -->
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white cursor-pointer hover:shadow-lg transition" onclick="window.location.href='{{ route('admin.users') }}'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold">Používatelia</h2>
                                    <p class="text-purple-100 mt-2">Spravovať používateľov a role</p>
                                </div>
                                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 6a3 3 0 11-6 0 3 3 0 016 0zm0 0a3 3 0 11-6 0 3 3 0 016 0zM13 11a4 4 0 11-8 0 4 4 0 018 0zm-2.5-9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Prilohy Card -->
                        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow p-6 text-white cursor-pointer hover:shadow-lg transition" onclick="window.location.href='{{ route('admin.additions') }}'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold">Prílohy</h2>
                                    <p class="text-amber-100 mt-2">Spravovať dostupné prílohy</p>
                                </div>
                                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H3a1 1 0 110-2h6V3a1 1 0 011-1z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 bg-gray-100 p-4 rounded text-sm text-gray-600">
                        <p><strong>Dostupní admini:</strong> Môžete spravovať kategórie, produkty, ich fotografie a používateľov systému.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
