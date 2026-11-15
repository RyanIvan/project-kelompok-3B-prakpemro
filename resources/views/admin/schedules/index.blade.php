@extends('layouts.admin')
@section('title', 'Jadwal')

@section('content')
    <x-admin.page-header title="Jadwal Perkuliahan" subtitle="Atur hari, jam, dan ruang untuk setiap kelas. Jadwal bentrok otomatis ditolak.">
        <x-admin.btn-add :href="route('admin.schedules.create')" label="Atur Jadwal" />
    </x-admin.page-header>

    <x-admin.filter-bar :action="route('admin.schedules.index')">
        <div class="w-40">
            <x-admin.select name="day" label="Hari" :options="array_combine(\App\Models\Schedule::DAYS, \App\Models\Schedule::DAYS)"
                            :value="request('day')" placeholder="Semua" />
        </div>
        <div class="w-56">
            <x-admin.select name="room_id" label="Ruang" :options="$rooms->pluck('name', 'id')"
                            :value="request('room_id')" placeholder="Semua" />
        </div>
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Hari</th><th>Jam</th><th>Kelas</th><th>Dosen</th><th>Ruang</th><th class="px-4 text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($schedules as $schedule)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-medium">{{ $schedule->day }}</td>
                        <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                        <td>{{ $schedule->courseClass->label }}</td>
                        <td>{{ $schedule->courseClass->lecturer->user->name }}</td>
                        <td>{{ $schedule->room->name }}</td>
                        <td class="px-4">
                            <x-admin.row-actions :edit="route('admin.schedules.edit', $schedule)" :destroy="route('admin.schedules.destroy', $schedule)" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $schedules->links() }}</div>
@endsection
