@extends('layouts.admin')
@section('title', 'Mata Kuliah')

@section('content')
    <x-admin.page-header title="Data Mata Kuliah" subtitle="Mata kuliah, SKS, semester, program studi, dan prasyarat.">
        <x-admin.btn-add :href="route('admin.courses.create')" label="Tambah Mata Kuliah" />
    </x-admin.page-header>

    <x-admin.filter-bar :action="route('admin.courses.index')">
        <div class="w-64">
            <x-admin.input name="q" label="Cari" :value="request('q')" placeholder="Kode / nama mata kuliah" />
        </div>
        <div class="w-56">
            <x-admin.select name="study_program_id" label="Program Studi" :options="$programs->pluck('full_name', 'id')"
                            :value="request('study_program_id')" placeholder="Semua" />
        </div>
        <div class="w-28">
            <x-admin.input name="semester" type="number" min="1" label="Semester" :value="request('semester')" />
        </div>
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Smt</th><th>Program Studi</th><th>Prasyarat</th><th>Kelas</th><th class="px-4 text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-mono text-xs">{{ $course->code }}</td>
                        <td class="font-medium">{{ $course->name }}</td>
                        <td>{{ $course->sks }}</td>
                        <td>{{ $course->semester }}</td>
                        <td>{{ $course->studyProgram?->full_name ?? 'Umum' }}</td>
                        <td class="text-xs text-gray-500">{{ $course->prerequisites->pluck('code')->join(', ') ?: '—' }}</td>
                        <td>{{ $course->classes_count }}</td>
                        <td class="px-4">
                            <x-admin.row-actions :edit="route('admin.courses.edit', $course)" :destroy="route('admin.courses.destroy', $course)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada mata kuliah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $courses->links() }}</div>
@endsection
