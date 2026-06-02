<form method="GET" 
    action="{{ $action }}"
    class="w-full max-w-md relative" 
    x-ref="form"
    x-data="{
        searchTerm: '{{ request()->input('search') }}',
        isLoading: false,
        timer: null,
        handleSearch() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.submitForm();
            }, 2000);
        },
        submitForm() {
            clearTimeout(this.timer);
            this.isLoading = true;
            this.$refs.form.submit();
        }
    }">
    <!-- Container -->
    <div class="relative overflow-hidden rounded-xl bg-gray-800 border border-gray-700 shadow-lg">
        <!-- Spinner -->
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
            x-show="isLoading"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <svg class="animate-spin h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <!-- Search Icon -->
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
            x-show="!isLoading">
            <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <!-- Search Input -->
        <input 
            type="text" 
            name="search" 
            placeholder="{{ $placeholder ?? 'Search...' }}" 
            x-model="searchTerm"
            class="block w-full pl-10 pr-4  
                text-base font-medium
                border-transparent rounded-xl
                bg-gray-800
                text-white
                placeholder-gray-400
                focus:outline-none focus:ring-2 focus:ring-blue-500
                transition-all duration-200"
            x-on:input="handleSearch()"
            x-on:keydown.enter.prevent="submitForm()"
            :class="{ 'opacity-50': isLoading }"
            :disabled="isLoading"
            x-init="$watch('isLoading', value => {
                if (!value) {
                    $nextTick(() => $el.focus());
                }
            })"
            x-effect="if (!isLoading && searchTerm) {
                $nextTick(() => $el.focus())
            }"
        >
    </div>
</form>

<style>
    /* Smooth transitions */
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
</style>