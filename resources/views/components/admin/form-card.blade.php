@props(['action', 'method' => 'POST', 'cancel'])

<form method="POST" action="{{ $action }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    @csrf
    @if ($method !== 'POST') @method($method) @endif

    <div class="grid md:grid-cols-2 gap-4">
        {{ $slot }}
    </div>

    <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
        <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow transition">
            <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan
        </button>
        <a href="{{ $cancel }}" class="text-sm text-gray-600 hover:underline">Batal</a>
    </div>
</form>
