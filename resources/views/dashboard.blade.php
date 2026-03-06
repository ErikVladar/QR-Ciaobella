<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    @auth
                        <div class="mt-6 flex flex-col  sm:flex-row gap-4">
                            @if(auth()->user()->isKitchen())
                                <a href="{{ route('kitchen.index') }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-yellow-600 text-white font-bold rounded-lg hover:bg-yellow-700 transition-colors">
                                    Do kuchyne
                                </a>
                            @endif

                            @if(auth()->user()->isWaiter())
                                <a href="{{ route('waiter.index') }}" 
                                   class="inline-flex items-center justify-center text-6xl px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">
                                    K pultu
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
