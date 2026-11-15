@extends('layouts.admin')
@section('title', $item ? 'Edit Jurusan' : 'Tambah Jurusan')

@section('content')
    <x-admin.page-header :title="$item ? 'Edit Jurusan' : 'Tambah Jurusan'" />

    <x-admin.form-card :action="$item ? route('admin.departments.update', $item) : route('admin.departments.store')"
                       :method="$item ? 'PUT' : 'POST'" :cancel="route('admin.departments.index')">
        <x-admin.input name="code" label="Kode" :value="$item?->code" required placeholder="mis. TI" />
        <x-admin.input name="name" label="Nama Jurusan" :value="$item?->name" required />
        <div class="md:col-span-2">
            <x-admin.select name="faculty_id" label="Fakultas (opsional)" :options="$faculties->pluck('name', 'id')"
                            :value="$item?->faculty_id" placeholder="— Tanpa fakultas —" />
        </div>
    </x-admin.form-card>
@endsection
