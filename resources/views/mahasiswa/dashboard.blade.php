@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <x-admin.page-header title="Halo, {{ auth()->user()->name }}!" subtitle="Selamat datang di Portal Akademik Mahasiswa.">
        <x-admin.btn-add :href="route('mahasiswa.katalog')" label="Ambil Mata Kuliah" />
    </x-admin.page-header>

    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @foreach ([
            ['Total Pengajuan', $totalApplied, 'fa-paper-plane', 'bg-blue-100 text-blue-600', 'Semua mata kuliah yang diajukan'],
            ['Kelas Disetujui', $totalApproved, 'fa-circle-check', 'bg-emerald-100 text-emerald-600', 'Telah divalidasi dosen'],
            ['Menunggu Validasi', $totalPending, 'fa-hourglass-half', 'bg-orange-100 text-orange-600', 'Menunggu persetujuan dosen'],
            ['Total SKS', $totalSks, 'fa-layer-group', 'bg-purple-100 text-purple-600', 'SKS yang telah disetujui'],
        ] as [$label, $value, $icon, $color, $hint])
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl {{ $color }}"><i class="fa-solid {{ $icon }}"></i></div>
                <div>
                    <p class="text-xs text-gray-500">{{ $label }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
                    <p class="text-[11px] text-gray-400">{{ $hint }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-gray-900">Pengajuan Mata Kuliah Terbaru</h3>
                <a href="{{ route('mahasiswa.krs') }}" class="text-sm text-blue-700 hover:underline">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-xs text-gray-500 uppercase border-b">
                        <tr><th class="py-2">Mata Kuliah</th><th>SKS</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($recent as $item)
                            <tr class="border-b last:border-0">
                                <td class="py-2.5">
                                    <p class="font-medium">{{ $item->course_name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $item->course_code }}</p>
                                </td>
                                <td>{{ $item->sks }}</td>
                                <td><x-status-badge :status="$item->status" /></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-6 text-center text-gray-400">Belum ada pengajuan mata kuliah.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-900 mb-3"><i class="fa-solid fa-id-card text-blue-600 mr-1"></i> Profil Mahasiswa</h3>
            <dl class="text-sm divide-y">
                @foreach ([
                    ['Nama Lengkap', auth()->user()->name],
                    ['NIM', $student->nim ?? '-'],
                    ['Email', auth()->user()->email],
                    ['Angkatan', $student->batch ?? '-'],
                    ['Program Studi', $student->study_program ?? '-'],
                ] as [$k, $v])
                    <div class="py-2 flex justify-between gap-4"><dt class="text-gray-500">{{ $k }}</dt><dd class="font-medium text-right">{{ $v }}</dd></div>
                @endforeach
                <div class="py-2 flex justify-between gap-4">
                    <dt class="text-gray-500">Status Akademik</dt>
                    <dd>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ strtolower($student->status ?? '') === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }}">{{ $student->status ?? '-' }}</span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
@endsection
