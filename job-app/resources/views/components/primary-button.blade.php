<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-cyan-600 to-indigo-600 hover:from-indigo-600 hover:to-cyan-600 text-sm font-semibold text-white uppercase tracking-wider rounded-md border-transparent transform hover:scale-105 focus:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:from-indigo-700 active:to-cyan-700 transition-all duration-300 ease-in-out']) }}>
    {{ $slot }}
</button>
