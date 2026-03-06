<nav class="bg-white p-2 shadow sticky top-0 z-50 h-16 overflow-visible" x-data="{ open: false }">
    <!-- Logo -->
    <div class="absolute left-0 top-0 z-20">
        <a href="{{ route('categories.index') }}" class="block">
            <div class="bg-black px-10 p-6 shadow overflow-visible" style="clip-path: polygon(0 0, 100% 0, calc(100% - 20px) 100%, 0 100%);">
                <img src="{{ asset('imgs/logo.png') }}" alt="..." class="h-8 mb-1">
            </div>
        </a>
    </div>

    <div class="max-w-7xl mx-auto flex justify-end items-center min-h-[56px]">

        <!-- Cart Icon (guest) or Hamburger (auth) -->
        <div>
            @guest
                <a href="{{ route('cart.view') }}" class="text-gray-700 hover:text-green-600 transition relative inline-block">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @php
                        $cartCount = collect(session()->get('cart', []))->sum('quantity');
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            @else
                <button @click="open = !open" class="text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endguest
        </div>
    </div>

    <!-- Mobile dropdown (absolute overlay) - only for authenticated users -->
    @auth
        <div x-show="open" @click.away="open = false" x-transition
            class="absolute top-full left-0 w-full bg-white shadow-md z-50 space-y-2 mt-1 p-4">
            <a href="{{ route('cart.view') }}" class="block px-4 py-2 hover:bg-gray-100 rounded">Košík</a>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 rounded">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded">Odhlásiť sa</button>
            </form>
        </div>
    @endauth
</nav>
