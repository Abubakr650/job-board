<div class="absolute inset-x-0 top-5 z-50 max-w-2xl mx-auto">
    @if (session('success'))
        <div x-data="{show: true}" 
             x-show="show" 
             x-transition 
             x-init="
                if (performance.navigation.type === performance.navigation.TYPE_BACK_FORWARD) {
                    show = false;
                } else {
                    setTimeout(() => show = false, 5000)
                }
             "
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" 
            role="alert">
            {{ session('success') }}
        </div>
    @endif
</div>