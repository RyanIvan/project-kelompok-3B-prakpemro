@extends('layouts.admin')
@section('title', $item ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah')

@section('content')
    @php
        $selectedPrereqs = old('prerequisites', $item?->prerequisites->pluck('id')->all() ?? []);
    @endphp

    <x-admin.page-header :title="$item ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah'" />

    <x-admin.form-card :action="$item ? route('admin.courses.update', $item) : route('admin.courses.store')"
                       :method="$item ? 'PUT' : 'POST'" :cancel="route('admin.courses.index')">
        <x-admin.input name="code" label="Kode" :value="$item?->code" required placeholder="mis. IF101" />
        <x-admin.input name="name" label="Nama Mata Kuliah" :value="$item?->name" required />
        <x-admin.input name="sks" type="number" min="1" max="8" label="SKS" :value="$item?->sks" required />
        <x-admin.input name="semester" type="number" min="1" max="14" label="Semester" :value="$item?->semester" required />
        <div class="md:col-span-2">
            <x-admin.select name="study_program_id" label="Program Studi (opsional)" :options="$programs->pluck('full_name', 'id')"
                            :value="$item?->study_program_id" placeholder="— Mata kuliah umum (semua prodi) —" />
        </div>

        <div class="md:col-span-2">
            <p class="block text-xs font-semibold text-gray-600 uppercase mb-1">Mata Kuliah Prasyarat</p>
            <div class="border border-gray-300 rounded-lg max-h-52 overflow-y-auto p-2 grid sm:grid-cols-2 gap-1">
                @forelse ($otherCourses as $other)
                    <label class="flex items-center gap-2 text-sm px-2 py-1 rounded hover:bg-gray-50">
                        <input type="checkbox" name="prerequisites[]" value="{{ $other->id }}" @checked(in_array($other->id, $selectedPrereqs))>
                        <span><span class="font-mono text-xs text-gray-500">{{ $other->code }}</span> {{ $other->name }}</span>
                    </label>
                @empty
                    <p class="text-sm text-gray-400 px-2 py-1">Belum ada mata kuliah lain.</p>
                @endforelse
            </div>
            @error('prerequisites.*')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </x-admin.form-card>
@endsection
