@extends('layouts.admin')
@section('title', $item ? 'Edit Ruang' : 'Tambah Ruang')

@section('content')
    <x-admin.page-header :title="$item ? 'Edit Ruang' : 'Tambah Ruang'" />

    <x-admin.form-card :action="$item ? route('admin.rooms.update', $item) : route('admin.rooms.store')"
                       :method="$item ? 'PUT' : 'POST'" :cancel="route('admin.rooms.index')">
        <x-admin.input name="code" label="Kode Ruang" :value="$item?->code" required placeholder="mis. A101" />
        <x-admin.input name="name" label="Nama Ruang" :value="$item?->name" required placeholder="mis. Ruang A101" />
        <x-admin.input name="capacity" type="number" min="1" label="Kapasitas" :value="$item?->capacity" required />
    </x-admin.form-card>
@endsection
