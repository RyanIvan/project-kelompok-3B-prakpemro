@extends('layouts.admin')
@section('title', 'Ruang')

@section('content')
    <x-admin.page-header title="Data Ruang" subtitle="Ruang kelas yang dipakai pada penjadwalan.">
        <x-admin.btn-add :href="route('admin.rooms.create')" label="Tambah Ruang" />
    </x-admin.page-header>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Kode</th><th>Nama Ruang</th><th>Kapasitas</th><th>Jadwal Terpakai</th><th class="px-4 text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($rooms as $room)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-mono text-xs">{{ $room->code }}</td>
                        <td class="font-medium">{{ $room->name }}</td>
                        <td>{{ $room->capacity }} orang</td>
                        <td>{{ $room->schedules_count }}</td>
                        <td class="px-4">
                            <x-admin.row-actions :edit="route('admin.rooms.edit', $room)" :destroy="route('admin.rooms.destroy', $room)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada ruang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $rooms->links() }}</div>
@endsection
