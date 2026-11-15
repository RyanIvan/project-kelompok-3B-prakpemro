@extends('layouts.admin')
@section('title', $item ? 'Edit Mahasiswa' : 'Tambah Mahasiswa')

@section('content')
    <x-admin.page-header :title="$item ? 'Edit Mahasiswa' : 'Tambah Mahasiswa'" />

    <x-admin.form-card :action="$item ? route('admin.students.update', $item) : route('admin.students.store')"
                       :method="$item ? 'PUT' : 'POST'" :cancel="route('admin.students.index')">
        <x-admin.input name="name" label="Nama Lengkap" :value="$item?->user->name" required />
        <x-admin.input name="email" type="email" label="Email" :value="$item?->user->email" required />
        <x-admin.input name="nim" label="NIM" :value="$item?->nim" required />
        <x-admin.input name="batch" type="number" label="Batch (Angkatan)" :value="$item?->batch ?? now()->year" required />
        <x-admin.select name="study_program_id" label="Program Studi" :options="$programs->pluck('full_name', 'id')"
                        :value="$item?->study_program_id" placeholder="— Pilih program studi —" required />
        <x-admin.select name="status" label="Status Mahasiswa" :options="['Aktif' => 'Aktif', 'Non-Aktif' => 'Non-Aktif']"
                        :value="$item?->status ?? 'Aktif'" required />
        <div class="md:col-span-2">
            <x-admin.input name="password" type="password" label="Kata Sandi"
                           :hint="$item ? 'Kosongkan jika tidak ingin mengubah kata sandi.' : 'Kosongkan untuk memakai NIM sebagai kata sandi awal.'" />
        </div>
    </x-admin.form-card>
@endsection
