<button 
    {{ $attributes->merge([
        'type' => 'submit', 
        'class' => 'inline-flex items-center justify-center px-6 py-2 border border-transparent
                   text-base font-medium rounded-lg text-white
                   bg-gradient-to-r from-gray-600 to-gray-800
                   hover:from-gray-700 hover:to-gray-900
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500
                   transform transition-all duration-200 ease-in-out
                   hover:scale-[1.02] active:scale-[0.98]
                   shadow-md hover:shadow-lg
                   disabled:opacity-60 disabled:cursor-not-allowed
                   min-w-[120px]'
    ]) }}
>
    <span class="relative flex items-center gap-2">
        {{ $slot }}
    </span>
</button>