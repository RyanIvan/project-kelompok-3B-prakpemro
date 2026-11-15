@extends('layouts.admin')
@section('title', 'Jurusan')

@section('content')
    <x-admin.page-header title="Data Jurusan" subtitle="Satu jurusan dapat memiliki beberapa program studi.">
        <x-admin.btn-add :href="route('admin.departments.create')" label="Tambah Jurusan" />
    </x-admin.page-header>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Kode</th><th>Nama Jurusan</th><th>Fakultas</th><th>Jumlah Prodi</th><th class="px-4 text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($departments as $department)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-mono text-xs">{{ $department->code }}</td>
                        <td class="font-medium">{{ $department->name }}</td>
                        <td>{{ $department->faculty?->name ?? '— (tanpa fakultas)' }}</td>
                        <td>{{ $department->study_programs_count }}</td>
                        <td class="px-4">
                            <x-admin.row-actions :edit="route('admin.departments.edit', $department)" :destroy="route('admin.departments.destroy', $department)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada jurusan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $departments->links() }}</div>
@endsection
