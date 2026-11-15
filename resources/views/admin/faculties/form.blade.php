@extends('layouts.admin')
@section('title', $item ? 'Edit Fakultas' : 'Tambah Fakultas')

@section('content')
    <x-admin.page-header :title="$item ? 'Edit Fakultas' : 'Tambah Fakultas'" />

    <x-admin.form-card :action="$item ? route('admin.faculties.update', $item) : route('admin.faculties.store')"
                       :method="$item ? 'PUT' : 'POST'" :cancel="route('admin.faculties.index')">
        <x-admin.input name="code" label="Kode" :value="$item?->code" required placeholder="mis. FT" />
        <x-admin.input name="name" label="Nama Fakultas" :value="$item?->name" required />
    </x-admin.form-card>
@endsection
