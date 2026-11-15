@props(['title', 'subtitle' => null])

<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
        @if ($subtitle)<p class="text-sm text-gray-500">{{ $subtitle }}</p>@endif
    </div>
    <div class="flex items-center gap-2">{{ $slot }}</div>
</div>
