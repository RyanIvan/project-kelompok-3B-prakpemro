@props(['action'])

<form method="GET" action="{{ $action }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4 flex flex-wrap items-end gap-3">
    {{ $slot }}
    <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg"><i class="fa-solid fa-magnifying-glass mr-1"></i> Cari</button>
    <a href="{{ $action }}" class="text-sm text-gray-500 hover:underline py-2">Reset</a>
</form>
