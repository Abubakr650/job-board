<div class="absolute inset-x-0 top-5 z-50 max-w-4xl mx-auto">
    @if (session('success'))
        <div x-data="{show: true}" 
             x-show="show" 
             x-transition 
             x-init="
                if (performance.navigation.type === performance.navigation.TYPE_BACK_FORWARD) {
                    show = false;
                } else {
                    setTimeout(() => show = false, 8000)
                }
             "
            class="border border-blue-300 bg-indigo-600 text-white px-4 py-3 rounded relative mb-4" 
            role="alert">
            {{ session('success') }}
        </div>
    @endif
</div>