@props([
    'categories' => [],
    'title' => 'Filter',
    'applyFilterRoute' => ''
])

<div
    x-data="{
        open: false,
        selectedFilters: [],
        categories: {{ json_encode($categories) }},
        toggle() {
            this.open = !this.open
        },
        close() {
            this.open = false
        },
        isSelected(value) {
            return this.selectedFilters.includes(value)
        },
        toggleFilter(value) {
            if (this.isSelected(value)) {
                this.selectedFilters = this.selectedFilters.filter(i => i !== value)
            } else {
                this.selectedFilters.push(value)
            }
        },
        clearFilters() {
            this.selectedFilters = []
        },
        applyFilters() {
            // You can modify this to handle the filter application
            window.location.href = '{{ $applyFilterRoute }}' + '?filters=' + this.selectedFilters.join(',');
            this.close()
        }
    }"
    @click.away="close()"
    class="relative w-full max-w-md px-3"
>
    <!-- Filter Button -->
    <button
        @click="toggle()"
        type="button"
        class="flex items-center w-full px-4 py-3 text-base font-medium text-white bg-gray-800 border border-gray-700 rounded-xl hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        {{ $title }}
        <span x-show="selectedFilters.length > 0" class="mr-2 bg-blue-500 text-white rounded-full px-2 py-0.5 text-xs">
            <span x-text="selectedFilters.length"></span>
        </span>
        <svg class="ml-auto h-5 w-5 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- List of filters -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 w-full rounded-xl bg-gray-800 border border-gray-700 shadow-lg"
    >
        <div class="p-3">
            <!-- Options -->
            <div class="space-y-2 p-3">
                <template x-for="category in categories" :key="category.id">
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer w-full rounded-lg p-2 hover:bg-gray-700 transition-colors duration-200">
                            <input
                                type="checkbox"
                                :value="category.value"
                                @click="toggleFilter(category.value)"
                                :checked="isSelected(category.value)"
                                class="form-checkbox h-5 w-5 text-blue-500 rounded border-gray-600 bg-gray-700 focus:ring-blue-500 focus:ring-offset-gray-800"
                            >
                            <span class="mr-3 text-white" x-text="category.name"></span>
                        </label>
                    </div>
                </template>
            </div>

            <!-- Buttons for filter and cancel -->
            <div class="mt-4 flex">
                <button
                    @click="applyFilters"
                    class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors duration-200"
                >
                    Filter
                </button>
            </div>
        </div>
    </div>
</div>