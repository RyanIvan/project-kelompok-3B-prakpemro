@extends('layouts.admin')
@section('title', $item ? 'Edit Kelas' : 'Buat Kelas')

@section('content')
    <x-admin.page-header :title="$item ? 'Edit Kelas' : 'Buat Kelas'" />

    <x-admin.form-card :action="$item ? route('admin.classes.update', $item) : route('admin.classes.store')"
                       :method="$item ? 'PUT' : 'POST'" :cancel="route('admin.classes.index')">
        <div class="md:col-span-2">
            <x-admin.select name="course_id" label="Mata Kuliah"
                            :options="$courses->mapWithKeys(fn ($c) => [$c->id => $c->code.' - '.$c->name])"
                            :value="$item?->course_id" placeholder="— Pilih mata kuliah —" required />
        </div>
        <x-admin.select name="lecturer_id" label="Dosen Pengampu"
                        :options="$lecturers->mapWithKeys(fn ($l) => [$l->id => $l->user->name])"
                        :value="$item?->lecturer_id" placeholder="— Pilih dosen —" required />
        <x-admin.select name="study_program_id" label="Untuk Program Studi" :options="$programs->pluck('full_name', 'id')"
                        :value="$item?->study_program_id" placeholder="— Umum (semua prodi) —" />
        <x-admin.input name="name" label="Nama Kelas" :value="$item?->name ?? 'A'" required placeholder="A / B / C" />
        <x-admin.input name="academic_year" label="Tahun Ajaran" :value="$item?->academic_year" required placeholder="2025/2026 Genap" />
        <x-admin.input name="capacity" type="number" min="1" label="Kapasitas" :value="$item?->capacity" required />
    </x-admin.form-card>
@endsection
