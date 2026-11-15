@extends('layouts.admin')
@section('title', $item ? 'Edit Dosen' : 'Tambah Dosen')

@section('content')
    <x-admin.page-header :title="$item ? 'Edit Dosen' : 'Tambah Dosen'" />

    <x-admin.form-card :action="$item ? route('admin.lecturers.update', $item) : route('admin.lecturers.store')"
                       :method="$item ? 'PUT' : 'POST'" :cancel="route('admin.lecturers.index')">
        <x-admin.input name="name" label="Nama Lengkap (beserta gelar)" :value="$item?->user->name" required />
        <x-admin.input name="email" type="email" label="Email" :value="$item?->user->email" required />
        <x-admin.input name="nip" label="NIP" :value="$item?->nip" required />
        <x-admin.input name="nidn" label="NIDN" :value="$item?->nidn" />
        <x-admin.input name="phone" label="Telepon" :value="$item?->phone" />
        <x-admin.input name="password" type="password" label="Kata Sandi"
                       :hint="$item ? 'Kosongkan jika tidak ingin mengubah kata sandi.' : 'Kosongkan untuk memakai NIP sebagai kata sandi awal.'" />
    </x-admin.form-card>
@endsection
