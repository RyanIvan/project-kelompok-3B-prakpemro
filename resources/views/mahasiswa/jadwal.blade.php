@extends('layouts.app')
@section('title', 'Jadwal Kuliah')

@section('content')
    <x-admin.page-header title="Jadwal Kuliah" subtitle="Jadwal mata kuliah yang sudah disetujui dosen." />

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Hari</th><th>Jam</th><th>Mata Kuliah</th><th>Ruangan</th><th>Tahun Akademik</th></tr>
            </thead>
            <tbody>
                @forelse ($schedules as $item)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">{{ $item->day }}</td>
                        <td class="font-mono text-xs">{{ $item->start_time }} - {{ $item->end_time }}</td>
                        <td>
                            <p class="font-medium">{{ $item->course_name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $item->course_code }}</p>
                        </td>
                        <td>{{ $item->room_name ?? $item->room_code ?? '-' }}</td>
                        <td>{{ $item->academic_year }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada jadwal perkuliahan yang disetujui.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
