@extends('layouts.admin')
@section('title', 'Program Studi')

@section('content')
    <x-admin.page-header title="Data Program Studi" subtitle="Program studi berada di bawah sebuah jurusan.">
        <x-admin.btn-add :href="route('admin.study-programs.create')" label="Tambah Program Studi" />
    </x-admin.page-header>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Kode</th><th>Program Studi</th><th>Jurusan</th><th>Fakultas</th><th>Mhs</th><th>MK</th><th class="px-4 text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($programs as $program)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-mono text-xs">{{ $program->code }}</td>
                        <td class="font-medium">{{ $program->full_name }}</td>
                        <td>{{ $program->department->name }}</td>
                        <td>{{ $program->department->faculty?->name ?? '—' }}</td>
                        <td>{{ $program->students_count }}</td>
                        <td>{{ $program->courses_count }}</td>
                        <td class="px-4">
                            <x-admin.row-actions :edit="route('admin.study-programs.edit', $program)" :destroy="route('admin.study-programs.destroy', $program)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada program studi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $programs->links() }}</div>
@endsection
