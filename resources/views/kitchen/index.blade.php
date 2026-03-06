<x-app-layout>
    <!-- Header -->
    <div class="bg-white border-b sticky top-0 z-10 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center mt-4 justify-between">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Kuchynské objednávky</h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto min-h-screen px-4 sm:px-6 lg:px-8 py-6">
        <!-- Tab Navigation -->
        <div class="flex gap-2 mb-6 border-b border-gray-200">
            <button onclick="showKitchenTab('processing')" id="kitchen-processing-tab" class="px-4 py-2 font-semibold border-b-2 border-blue-600 text-blue-600">
                Na spracovanie
            </button>
            <button onclick="showKitchenTab('finished')" id="kitchen-finished-tab" class="px-4 py-2 font-semibold border-b-2 border-gray-200 text-gray-600 hover:text-gray-900">
                Dokončené
            </button>
        </div>

        <!-- Processing Orders Tab -->
        <div id="kitchen-processing-content" class="kitchen-tab-content">
            @livewire('kitchen-processing')
        </div>

        <!-- Finished Orders Tab -->
        <div id="kitchen-finished-content" class="kitchen-tab-content hidden">
            @livewire('kitchen-finished')
        </div>
    </div>

    <script>
        function showKitchenTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.kitchen-tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Remove active state from all tab buttons
            document.querySelectorAll('[id^="kitchen-"][id$="-tab"]').forEach(btn => {
                btn.classList.remove('border-b-2', 'border-blue-600', 'text-blue-600');
                btn.classList.add('border-b-2', 'border-gray-200', 'text-gray-600');
            });

            // Show selected tab
            document.getElementById('kitchen-' + tabName + '-content').classList.remove('hidden');

            // Add active state to clicked tab
            document.getElementById('kitchen-' + tabName + '-tab').classList.remove('border-b-2', 'border-gray-200', 'text-gray-600');
            document.getElementById('kitchen-' + tabName + '-tab').classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
        }
    </script>
</x-app-layout>
