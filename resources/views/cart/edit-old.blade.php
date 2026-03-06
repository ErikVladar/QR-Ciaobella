<x-app-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit additions for: {{ $product->name }}</h1>

        <form action="{{ route('cart.updateAdditions', $id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-2">Doplnky (vyberte maximálne 4)</label>
                @foreach($additions as $addition)
                    <label class="flex items-center mb-2">
                        <input type="checkbox" name="additions[]" value="{{ $addition->id }}" class="mr-2"
                            {{ in_array($addition->id, $item['addition_ids'] ?? []) ? 'checked' : '' }}>
                        <span>{{ $addition->name }} (+{{ number_format($addition->price, 2) }}€)</span>
                    </label>
                @endforeach
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                <a href="{{ route('cart.view') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    // limit checkboxes to 4 selections
    (function () {
        const checkboxes = document.querySelectorAll('input[name="additions[]"]');
        function enforceLimit() {
            const checked = Array.from(checkboxes).filter(c => c.checked);
            if (checked.length >= 4) {
                // disable unchecked boxes
                checkboxes.forEach(c => { if (!c.checked) c.disabled = true; });
            } else {
                checkboxes.forEach(c => c.disabled = false);
            }
        }
        checkboxes.forEach(c => c.addEventListener('change', enforceLimit));
        document.addEventListener('DOMContentLoaded', enforceLimit);
    })();
</script>
