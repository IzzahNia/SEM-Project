{{-- resources/views/components/textarea.blade.php --}}
@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-white text-gray font-semibold border border-slate-200 focus:border-0 focus:ring-primary-400 focus:ring-1 focus:text-gray-700 rounded-md shadow-sm placeholder:text-sm placeholder:text-slate-400 placeholder:text-opacity-60']) !!}>
    {{ trim($slot) }}
</textarea>

<style>
    textarea {
        resize: vertical; /* Allow vertical resizing */
        min-height: 40px; /* Set a minimum height */
        max-height: 150px; /* Set a maximum height */
    }
</style>