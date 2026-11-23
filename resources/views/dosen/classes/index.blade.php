@extends('layouts.dosen')

@section('title', 'Kelas Saya')

@section('content')
<div class="space-y-6">
    <!-- HEADER & SEARCH -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Kelas yang Diampu</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar kelas perkuliahan, peserta mahasiswa, dan validasi KRS.</p>
        </div>

        <form method="GET" action="{{ route('dosen.classes.index') }}" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari mata kuliah..." class="text-xs pl-8 pr-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-600 shadow-sm w-48 sm:w-60">
            </div>

            @if ($academicYears->isNotEmpty())
                <select name="academic_year" onchange="this.form.submit()" class="text-xs bg-white border border-slate-300 rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-600 shadow-sm">
                    <option value="">-- Semua Tahun Ajaran --</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year }}" {{ request('academic_year') === $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            @endif

            <button type="submit" class="text-xs bg-blue-900 hover:bg-blue-800 text-white px-3 py-2 rounded-lg font-semibold transition shadow-sm">
                Filter
            </button>

            @if (request()->hasAny(['q', 'academic_year']))
                <a href="{{ route('dosen.classes.index') }}" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-lg transition font-medium">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- CARDS GRID -->
    @if ($classes->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-sm">
            <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 text-xl">
                <i class="fa-solid fa-chalkboard"></i>
            </div>
            <p class="font-bold text-slate-700 text-base">Tidak ada kelas ditemukan</p>
            <p class="text-xs text-slate-400 mt-1">Belum ada kelas yang terdaftar atau hasil pencarian tidak cocok.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($classes as $item)
                @php
                    $percent = $item->capacity > 0 ? min(100, round(($item->approved_count / $item->capacity) * 100)) : 0;
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 hover:border-blue-300 shadow-sm hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <span class="text-[11px] font-mono px-2 py-0.5 bg-slate-100 text-slate-600 rounded font-semibold">
                                {{ $item->course?->code ?? '-' }}
                            </span>
                            <span class="text-xs px-2.5 py-0.5 bg-blue-100 text-blue-900 font-bold rounded-full">
                                Kelas {{ $item->name }}
                            </span>
                        </div>

                        <h3 class="font-bold text-slate-900 text-base leading-snug line-clamp-2 mb-1">
                            {{ $item->course?->name ?? '-' }}
                        </h3>

                        <p class="text-xs text-slate-500 mb-3">
                            {{ $item->studyProgram?->name ?? 'Semua Prodi' }} &bull; {{ $item->course?->sks ?? 0 }} SKS
                        </p>

                        <!-- Jadwal -->
                        <div class="text-xs text-slate-600 bg-slate-50 rounded-xl p-3 border border-slate-100 mb-4 space-y-1">
                            <div class="font-semibold text-slate-700 flex items-center gap-1.5 mb-1 text-[11px] uppercase tracking-wider">
                                <i class="fa-regular fa-calendar-days text-blue-600"></i> Jadwal Kuliah:
                            </div>
                            @forelse ($item->schedules as $sched)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-700">{{ $sched->day }}, {{ $sched->start_time }}-{{ $sched->end_time }}</span>
                                    <span class="text-slate-500">{{ $sched->room?->name ?? 'TBA' }}</span>
                                </div>
                            @empty
                                <p class="text-slate-400 italic text-xs">Jadwal belum ditentukan.</p>
                            @endforelse
                        </div>

                        <!-- Kapasitas & Kuota -->
                        <div>
                            <div class="flex justify-between items-center text-xs mb-1.5">
                                <span class="text-slate-500">Mahasiswa Terdaftar:</span>
                                <span class="font-bold text-slate-800">{{ $item->approved_count }} / {{ $item->capacity }}</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>

                        @if ($item->pending_count > 0)
                            <div class="mt-3 py-1.5 px-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-xs flex items-center justify-between">
                                <span><i class="fa-solid fa-clock mr-1"></i> Permintaan KRS Baru</span>
                                <span class="font-bold bg-amber-200 text-amber-900 px-1.5 py-0.2 rounded text-[11px]">{{ $item->pending_count }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 bg-slate-50/70 border-t border-slate-100 grid grid-cols-2 gap-2">
                        <a href="{{ route('dosen.classes.show', $item) }}" class="text-xs bg-white hover:bg-blue-50 text-blue-900 font-semibold py-2 px-3 rounded-lg border border-blue-200 text-center transition shadow-sm inline-flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-users"></i> Mahasiswa
                        </a>
                        <a href="{{ route('dosen.grades.edit', $item) }}" class="text-xs bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2 px-3 rounded-lg text-center transition shadow-sm inline-flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-star"></i> Input Nilai
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $classes->links() }}
        </div>
    @endif
</div>
@endsection
