@extends('layouts.dosen')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="space-y-6">
    <!-- WELCOME CARD -->
    <div class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="inline-block px-3 py-1 bg-yellow-400 text-blue-950 text-xs font-bold rounded-full mb-3 shadow-sm">
                <i class="fa-solid fa-id-badge mr-1"></i> SIAKAD Portal Dosen
            </span>
            <h2 class="text-2xl font-bold leading-tight">Selamat Datang, {{ auth()->user()->name }}</h2>
            <p class="text-blue-100 text-sm mt-1">Kelola jadwal perkuliahan, persetujuan KRS mahasiswa, dan input nilai dengan mudah.</p>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 text-xs space-y-1.5 min-w-[240px]">
            <div class="flex justify-between">
                <span class="text-blue-200">NIP:</span>
                <span class="font-semibold">{{ $lecturer->nip ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-blue-200">NIDN:</span>
                <span class="font-semibold">{{ $lecturer->nidn ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-blue-200">Email:</span>
                <span class="font-semibold">{{ auth()->user()->email }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-blue-200">Kontak:</span>
                <span class="font-semibold">{{ $lecturer->phone ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Kelas Diampu -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Kelas Diampu</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_classes'] }}</h3>
            </div>
        </div>

        <!-- Mahasiswa Diajar -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Mahasiswa Aktif</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['total_students'] }}</h3>
            </div>
        </div>

        <!-- Pending KRS -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Validasi KRS</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['pending_krs'] }}</h3>
                <span class="text-[11px] text-amber-600 font-medium">Menunggu persetujuan</span>
            </div>
        </div>

        <!-- Jadwal Hari Ini -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Jadwal Hari Ini</p>
                <h3 class="text-2xl font-bold text-slate-800">{{ $stats['today_schedules_count'] }}</h3>
                <span class="text-[11px] text-sky-700 font-semibold">{{ $today }}</span>
            </div>
        </div>
    </div>

    <!-- MAIN GRID: JADWAL HARI INI & KELAS TERBARU -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Jadwal Hari Ini (2 kolom di layar besar) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 bg-blue-600 rounded-full animate-pulse"></div>
                    <h3 class="font-bold text-slate-800 text-base">Jadwal Mengajar Hari Ini ({{ $today }})</h3>
                </div>
                <a href="{{ route('dosen.schedules.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                    Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            @if ($todaySchedules->isEmpty())
                <div class="py-12 text-center text-slate-500">
                    <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 text-xl">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <p class="font-semibold text-slate-700">Tidak ada jadwal kuliah hari ini</p>
                    <p class="text-xs text-slate-400 mt-1">Anda tidak memiliki jam mengajar yang terdaftar untuk hari {{ $today }}.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($todaySchedules as $schedule)
                        <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-blue-50/40 hover:border-blue-200 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-900 font-bold flex flex-col items-center justify-center text-xs flex-shrink-0">
                                    <span>{{ $schedule->start_time }}</span>
                                    <span class="text-[10px] text-blue-600 font-normal">{{ $schedule->end_time }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">
                                        {{ $schedule->courseClass?->course?->name ?? '-' }}
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                                        <span class="bg-white px-2 py-0.5 rounded border border-slate-200 font-medium">Kelas {{ $schedule->courseClass?->name ?? '-' }}</span>
                                        <span>&bull;</span>
                                        <span><i class="fa-solid fa-door-open mr-1 text-slate-400"></i>{{ $schedule->room?->name ?? 'Ruang TBA' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $schedule->courseClass?->course?->sks ?? 0 }} SKS</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-200">
                                <a href="{{ route('dosen.classes.show', $schedule->courseClass) }}" class="text-xs bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 font-semibold px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1 shadow-sm">
                                    <i class="fa-solid fa-users"></i> Peserta
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Kelas yang Diampu (1 kolom) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-800 text-base">Kelas Diampu</h3>
                <a href="{{ route('dosen.classes.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                    Semua ({{ $stats['total_classes'] }})
                </a>
            </div>

            @if ($classes->isEmpty())
                <div class="py-12 text-center text-slate-500 my-auto">
                    <p class="font-medium text-sm">Belum ada kelas yang diampu.</p>
                </div>
            @else
                <div class="space-y-3 flex-1">
                    @foreach ($classes as $item)
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 hover:border-slate-300 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-slate-800 text-sm leading-snug">{{ $item->course?->name ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Kelas {{ $item->name }} &bull; {{ $item->academic_year }}</p>
                                </div>
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $item->pending_count > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $item->approved_count }}/{{ $item->capacity }} mhs
                                </span>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                <a href="{{ route('dosen.classes.show', $item) }}" class="text-blue-700 hover:text-blue-900 font-semibold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-list-check"></i> KRS ({{ $item->pending_count }} baru)
                                </a>
                                <a href="{{ route('dosen.grades.edit', $item) }}" class="text-indigo-700 hover:text-indigo-900 font-semibold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-star"></i> Nilai
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
