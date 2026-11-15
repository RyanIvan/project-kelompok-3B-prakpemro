@extends('layouts.admin')
@section('title', 'Dosen')

@section('content')
    <x-admin.page-header title="Data Dosen" subtitle="Kelola akun dan data dosen pengampu.">
        <x-admin.btn-add :href="route('admin.lecturers.create')" label="Tambah Dosen" />
    </x-admin.page-header>

    <x-admin.filter-bar :action="route('admin.lecturers.index')">
        <div class="w-72">
            <x-admin.input name="q" label="Cari" :value="request('q')" placeholder="Nama / NIP / NIDN / email" />
        </div>
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">NIP</th><th>NIDN</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Kelas</th><th class="px-4 text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($lecturers as $lecturer)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-mono text-xs">{{ $lecturer->nip }}</td>
                        <td class="font-mono text-xs">{{ $lecturer->nidn ?? '—' }}</td>
                        <td class="font-medium">{{ $lecturer->user->name }}</td>
                        <td class="text-gray-500">{{ $lecturer->user->email }}</td>
                        <td>{{ $lecturer->phone ?? '—' }}</td>
                        <td>{{ $lecturer->classes_count }}</td>
                        <td class="px-4">
                            <x-admin.row-actions :edit="route('admin.lecturers.edit', $lecturer)" :destroy="route('admin.lecturers.destroy', $lecturer)"
                                                 confirm="Hapus dosen ini beserta akunnya?" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Tidak ada data dosen.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $lecturers->links() }}</div>
@endsection
