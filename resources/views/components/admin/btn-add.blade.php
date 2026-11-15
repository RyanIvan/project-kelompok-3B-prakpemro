@props(['href', 'label'])

<a href="{{ $href }}" class="inline-flex items-center gap-2 bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow transition">
    <i class="fa-solid fa-plus text-xs"></i> {{ $label }}
</a>
