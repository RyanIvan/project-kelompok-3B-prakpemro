@extends('layouts.admin')
@section('title', $item ? 'Edit Program Studi' : 'Tambah Program Studi')

@section('content')
    <x-admin.page-header :title="$item ? 'Edit Program Studi' : 'Tambah Program Studi'" />

    <x-admin.form-card :action="$item ? route('admin.study-programs.update', $item) : route('admin.study-programs.store')"
                       :method="$item ? 'PUT' : 'POST'" :cancel="route('admin.study-programs.index')">
        <x-admin.input name="code" label="Kode" :value="$item?->code" required placeholder="mis. TI-S1" />
        <x-admin.input name="name" label="Nama Program Studi" :value="$item?->name" required placeholder="mis. Informatika" />
        <x-admin.select name="department_id" label="Jurusan" :options="$departments->pluck('name', 'id')"
                        :value="$item?->department_id" placeholder="— Pilih jurusan —" required />
        <x-admin.select name="degree" label="Jenjang" :options="array_combine(\App\Models\StudyProgram::DEGREES, \App\Models\StudyProgram::DEGREES)"
                        :value="$item?->degree ?? 'S1'" required />
    </x-admin.form-card>
@endsection
