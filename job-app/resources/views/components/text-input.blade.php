@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block mt-1 w-full bg-white/10 text-white border-white/10 focused:border-indigo-500 rounded-lg focus:ring-indigo-500 shadow-sm']) }}>
