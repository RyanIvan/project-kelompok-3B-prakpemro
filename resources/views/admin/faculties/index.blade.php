@extends('layouts.admin')
@section('title', 'Fakultas')

@section('content')
    <x-admin.page-header title="Data Fakultas" subtitle="Fakultas bersifat opsional: jurusan boleh tidak berada di bawah fakultas.">
        <x-admin.btn-add :href="route('admin.faculties.create')" label="Tambah Fakultas" />
    </x-admin.page-header>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Kode</th><th>Nama Fakultas</th><th>Jumlah Jurusan</th><th class="px-4 text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($faculties as $faculty)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-mono text-xs">{{ $faculty->code }}</td>
                        <td class="font-medium">{{ $faculty->name }}</td>
                        <td>{{ $faculty->departments_count }}</td>
                        <td class="px-4">
                            <x-admin.row-actions :edit="route('admin.faculties.edit', $faculty)" :destroy="route('admin.faculties.destroy', $faculty)" confirm="Hapus fakultas ini? Jurusan di bawahnya akan menjadi tanpa fakultas." />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada fakultas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $faculties->links() }}</div>
@endsection
