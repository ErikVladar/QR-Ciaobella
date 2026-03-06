<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            Čašnícky panel
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="flex gap-2 mb-6 border-b border-gray-200">
                <button onclick="showWaiterTab('active')" id="waiter-active-tab" class="px-4 py-2 font-semibold border-b-2 border-blue-600 text-blue-600">
                    Aktívne objednávky
                </button>
                <button onclick="showWaiterTab('history')" id="waiter-history-tab" class="px-4 py-2 font-semibold border-b-2 border-gray-200 text-gray-600 hover:text-gray-900">
                    História (Podané)
                </button>
            </div>

            <!-- Active Orders Tab -->
            <div id="waiter-active-content" class="waiter-tab-content">
                @livewire('waiter-active')
            </div>

            <!-- History Tab -->
            <div id="waiter-history-content" class="waiter-tab-content hidden">
                @livewire('waiter-history')
            </div>
        </div>
    </div>

    <script>
        function showWaiterTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.waiter-tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Remove active state from all tab buttons
            document.querySelectorAll('[id^="waiter-"][id$="-tab"]').forEach(btn => {
                btn.classList.remove('border-b-2', 'border-blue-600', 'text-blue-600');
                btn.classList.add('border-b-2', 'border-gray-200', 'text-gray-600');
            });

            // Show selected tab
            document.getElementById('waiter-' + tabName + '-content').classList.remove('hidden');

            // Add active state to clicked tab
            document.getElementById('waiter-' + tabName + '-tab').classList.remove('border-b-2', 'border-gray-200', 'text-gray-600');
            document.getElementById('waiter-' + tabName + '-tab').classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
        }
    </script>
</x-app-layout>
