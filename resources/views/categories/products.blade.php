<x-app-layout>
    <!-- Header -->
    <div class="bg-white border-b sticky top-0 z-10 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('categories.index') }}" class="text-gray-600 mt-4 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl mt-4 sm:text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
            </div>
        </div>
    </div>

    @php
        $pizzaAdditions = $category->has_prilohy ? \App\Models\PizzaAddition::all() : collect();
    @endphp

    <!-- Products List -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24">
        <div class="space-y-4">
            @foreach($products as $product)
                <form action="{{ route('cart.add', $product->id) }}" method="POST"
                      class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 hover:shadow-md transition-shadow">
                    @csrf

                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <h2 class="font-bold text-lg sm:text-xl text-gray-900">{{ $product->name }}</h2>
                                    @if($product->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $product->description }}</p>
                                    @endif
                                </div>

                                <p class="text-xl sm:text-2xl font-bold text-green-600 whitespace-nowrap">
                                    {{ number_format($product->price, 2) }}€
                                </p>
                            </div>

                            @if($product->alergens)
                                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded px-2 py-1 mt-3 inline-block">
                                    <span class="font-semibold">Alergény:</span> {{ $product->alergens }}
                                </p>
                            @endif

                            @if($category->has_prilohy)
                                <p class="text-xs text-gray-500 mt-3">
                                    Pri pridaní do košíka sa opýtame, či chcete doplniť prílohy.
                                </p>
                            @endif
                        </div>

                        <div class="lg:w-52 lg:shrink-0">
                            @if($category->has_prilohy)
                                <button type="button"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm"
                                        onclick="window.openAdditionsModal(this.form)">
                                    Pridať do košíka
                                </button>

                                <dialog class="additions-modal w-[calc(100%-1.5rem)] sm:w-full max-w-lg rounded-2xl p-0 backdrop:bg-black/50">
                                    <div class="p-6 sm:p-7">
                                        <div class="flex items-start justify-between gap-4 mb-4">
                                            <div>
                                                <h3 class="text-lg sm:text-xl font-bold text-gray-900">{{ $product->name }}</h3>
                                                <p class="text-sm text-gray-600 mt-1">Chcete si pridať aj prílohy?</p>
                                            </div>
                                            <button type="button" class="text-gray-400 hover:text-gray-600 transition" onclick="window.closeAdditionsModal(this)">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                            <div class="flex items-center justify-between gap-3 mb-3">
                                                <label class="text-sm font-bold text-gray-700">🍕 Prílohy (max 4)</label>
                                                <span class="text-xs text-gray-500">Nepovinné</span>
                                            </div>

                                            <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                                                @foreach($pizzaAdditions as $addition)
                                                    <label class="flex items-center gap-2 cursor-pointer bg-white hover:bg-gray-50 p-2 rounded-lg transition border border-gray-100">
                                                        <input type="checkbox" name="additions[]" value="{{ $addition->id }}"
                                                               class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                                                        <span class="text-sm text-gray-700 flex-1">{{ $addition->name }}</span>
                                                        <span class="text-sm font-semibold text-green-600">+{{ number_format($addition->price, 2) }}€</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="mt-5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                            <button type="button"
                                                    class="px-4 py-3 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition"
                                                    onclick="window.submitWithoutAdditions(this)">
                                                Bez príloh
                                            </button>
                                            <button type="submit"
                                                    class="px-4 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold transition shadow-sm">
                                                Pridať s výberom
                                            </button>
                                        </div>
                                    </div>
                                </dialog>
                            @else
                                <button type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors shadow-sm">
                                    Pridať do košíka
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    </div>

    <script>
        window.openAdditionsModal = function (form) {
            const modal = form.querySelector('.additions-modal');

            if (modal) {
                modal.showModal();
            }
        };

        window.closeAdditionsModal = function (button) {
            const modal = button.closest('.additions-modal');

            if (modal) {
                modal.close();
            }
        };

        window.submitWithoutAdditions = function (button) {
            const modal = button.closest('.additions-modal');
            const form = button.closest('form');

            if (!form) {
                return;
            }

            form.querySelectorAll('input[name="additions[]"]:checked').forEach((input) => {
                input.checked = false;
            });

            if (modal) {
                modal.close();
            }

            form.requestSubmit();
        };

        document.querySelectorAll('.additions-modal').forEach((modal) => {
            modal.addEventListener('click', (event) => {
                const dialogDimensions = modal.getBoundingClientRect();
                const clickedOutside =
                    event.clientX < dialogDimensions.left ||
                    event.clientX > dialogDimensions.right ||
                    event.clientY < dialogDimensions.top ||
                    event.clientY > dialogDimensions.bottom;

                if (clickedOutside) {
                    modal.close();
                }
            });
        });
    </script>
</x-app-layout>
