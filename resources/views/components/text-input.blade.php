@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-gray-800 border-gray-600 text-white focus:border-blue-400 focus:ring-blue-400 rounded-md shadow-sm placeholder-gray-400']) }}>
