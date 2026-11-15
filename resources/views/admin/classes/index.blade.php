@extends('layouts.admin')
@section('title', 'Kelas')

@section('content')
    <x-admin.page-header title="Data Kelas" subtitle="Kelas perkuliahan: mata kuliah + dosen pengampu + tahun ajaran + program studi.">
        <x-admin.btn-add :href="route('admin.classes.create')" label="Buat Kelas" />
    </x-admin.page-header>

    <x-admin.filter-bar :action="route('admin.classes.index')">
        <div class="w-64">
            <x-admin.input name="q" label="Cari" :value="request('q')" placeholder="Kode / nama mata kuliah" />
        </div>
        <div class="w-56">
            <x-admin.select name="academic_year" label="Tahun Ajaran" :options="$academicYears->combine($academicYears)"
                            :value="request('academic_year')" placeholder="Semua" />
        </div>
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Mata Kuliah</th><th>Kelas</th><th>Dosen</th><th>Prodi</th><th>Tahun Ajaran</th><th>Terisi</th><th class="px-4 text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($classes as $class)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <span class="font-mono text-xs text-gray-500">{{ $class->course->code }}</span>
                            <span class="font-medium">{{ $class->course->name }}</span>
                        </td>
                        <td>{{ $class->name }}</td>
                        <td>{{ $class->lecturer->user->name }}</td>
                        <td>{{ $class->studyProgram?->full_name ?? 'Umum' }}</td>
                        <td>{{ $class->academic_year }}</td>
                        <td>
                            <span class="{{ $class->enrolled_count >= $class->capacity ? 'text-red-600 font-semibold' : '' }}">{{ $class->enrolled_count }}/{{ $class->capacity }}</span>
                        </td>
                        <td class="px-4">
                            <x-admin.row-actions :edit="route('admin.classes.edit', $class)" :destroy="route('admin.classes.destroy', $class)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada kelas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $classes->links() }}</div>
@endsection
