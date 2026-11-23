@extends('layouts.app')
@section('title', 'Presensi')

@section('content')
    <x-admin.page-header title="Riwayat Presensi" subtitle="Pantau kehadiran Anda dalam kegiatan perkuliahan." />

    @unless ($presensiAvailable)
        <div class="mb-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-info mr-1"></i> Fitur presensi belum diaktifkan pada sistem ini.
        </div>
    @endunless

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Mata Kuliah</th><th>Pertemuan</th><th>Tanggal</th><th>Status Kehadiran</th></tr>
            </thead>
            <tbody>
                @forelse ($attendances as $item)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $item->course_name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $item->course_code }}</p>
                        </td>
                        <td>{{ $item->meeting_number }}</td>
                        <td>{{ $item->meeting_date }}</td>
                        <td><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-200 text-gray-600">{{ $item->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data presensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
