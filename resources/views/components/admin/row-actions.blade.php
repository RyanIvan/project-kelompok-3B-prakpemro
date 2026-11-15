@props(['edit', 'destroy', 'confirm' => 'Yakin ingin menghapus data ini?'])

<div class="flex items-center justify-end gap-2">
    <a href="{{ $edit }}" class="text-blue-700 hover:bg-blue-50 px-2.5 py-1.5 rounded-lg text-xs font-semibold" title="Edit">
        <i class="fa-solid fa-pen-to-square"></i> Edit
    </a>
    <form method="POST" action="{{ $destroy }}" onsubmit="return confirm(@js($confirm))">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:bg-red-50 px-2.5 py-1.5 rounded-lg text-xs font-semibold" title="Hapus">
            <i class="fa-solid fa-trash"></i> Hapus
        </button>
    </form>
</div>
