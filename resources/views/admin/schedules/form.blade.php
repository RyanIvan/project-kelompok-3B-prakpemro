@extends('layouts.admin')
@section('title', $item ? 'Edit Jadwal' : 'Atur Jadwal')

@section('content')
    <x-admin.page-header :title="$item ? 'Edit Jadwal' : 'Atur Jadwal'" />

    <x-admin.form-card :action="$item ? route('admin.schedules.update', $item) : route('admin.schedules.store')"
                       :method="$item ? 'PUT' : 'POST'" :cancel="route('admin.schedules.index')">
        <div class="md:col-span-2">
            <x-admin.select name="class_id" label="Kelas"
                            :options="$classes->mapWithKeys(fn ($c) => [$c->id => $c->label.' ('.$c->academic_year.') - '.$c->lecturer->user->name])"
                            :value="$item?->class_id" placeholder="— Pilih kelas —" required />
        </div>
        <x-admin.select name="room_id" label="Ruang" :options="$rooms->mapWithKeys(fn ($r) => [$r->id => $r->name.' (kap. '.$r->capacity.')'])"
                        :value="$item?->room_id" placeholder="— Pilih ruang —" required />
        <x-admin.select name="day" label="Hari" :options="array_combine(\App\Models\Schedule::DAYS, \App\Models\Schedule::DAYS)"
                        :value="$item?->day" placeholder="— Pilih hari —" required />
        <x-admin.input name="start_time" type="time" label="Jam Mulai" :value="$item?->start_time" required />
        <x-admin.input name="end_time" type="time" label="Jam Selesai" :value="$item?->end_time" required />
    </x-admin.form-card>
@endsection
