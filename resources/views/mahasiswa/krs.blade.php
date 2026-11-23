@extends('layouts.app')
@section('title', 'KRS Saya')

@section('content')
    <x-admin.page-header title="KRS Saya" subtitle="Daftar mata kuliah yang diajukan beserta status persetujuan dosen.">
        <x-admin.btn-add :href="route('mahasiswa.katalog')" label="Ambil Mata Kuliah" />
    </x-admin.page-header>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Mata Kuliah</th><th>SKS</th><th>Dosen</th><th>Tahun Akademik</th><th>Diajukan</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($enrollments as $item)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $item->course_name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $item->course_code }}</p>
                        </td>
                        <td>{{ $item->sks }}</td>
                        <td>{{ $item->lecturer_name ?? '-' }}</td>
                        <td>{{ $item->academic_year }}</td>
                        <td class="text-gray-500">{{ $item->enrolled_at ? \Illuminate\Support\Carbon::parse($item->enrolled_at)->format('d M Y') : '-' }}</td>
                        <td><x-status-badge :status="$item->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada mata kuliah yang diajukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="text-xs text-gray-500 mt-3">Validasi pengajuan dilakukan oleh dosen pengampu.</p>
@endsection
