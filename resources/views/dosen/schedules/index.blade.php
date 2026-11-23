@extends('layouts.dosen')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="space-y-6">
    <!-- HEADER & FILTER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Jadwal Mengajar</h2>
            <p class="text-xs text-gray-500 mt-1">Daftar agenda perkuliahan yang Anda ampu.</p>
        </div>

        <form method="GET" action="{{ route('dosen.schedules.index') }}" class="flex flex-wrap items-center gap-2">
            <select name="day" onchange="this.form.submit()" class="text-xs bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600 shadow-sm">
                <option value="">-- Semua Hari --</option>
                @foreach ($days as $dayOption)
                    <option value="{{ $dayOption }}" {{ request('day') === $dayOption ? 'selected' : '' }}>{{ $dayOption }}</option>
                @endforeach
            </select>

            @if ($academicYears->isNotEmpty())
                <select name="academic_year" onchange="this.form.submit()" class="text-xs bg-white border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600 shadow-sm">
                    <option value="">-- Semua Tahun Ajaran --</option>
                    @foreach ($academicYears as $year)
                        <option value="{{ $year }}" {{ request('academic_year') === $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            @endif

            @if (request()->hasAny(['day', 'academic_year']))
                <a href="{{ route('dosen.schedules.index') }}" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-lg transition font-medium">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- TABEL JADWAL -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if ($schedules->isEmpty())
            <div class="py-16 text-center text-gray-500">
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400 text-xl">
                    <i class="fa-solid fa-calendar-xmark"></i>
                </div>
                <p class="font-semibold text-gray-700 text-sm">Tidak ada jadwal kuliah ditemukan</p>
                <p class="text-xs text-gray-400 mt-1">Coba sesuaikan filter pencarian hari atau tahun ajaran.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700">
                    <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 border-b border-gray-200 font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Hari & Waktu</th>
                            <th class="px-5 py-3.5">Mata Kuliah</th>
                            <th class="px-5 py-3.5 text-center">Kelas</th>
                            <th class="px-5 py-3.5 text-center">SKS</th>
                            <th class="px-5 py-3.5">Ruangan</th>
                            <th class="px-5 py-3.5">Tahun Ajaran</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($schedules as $schedule)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <span class="inline-block px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-md font-bold text-xs border border-emerald-200">
                                            {{ $schedule->day }}
                                        </span>
                                        <span class="text-xs font-semibold text-gray-800">
                                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-gray-800">{{ $schedule->courseClass?->course?->name ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-400 font-mono">{{ $schedule->courseClass?->course?->code ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-2.5 py-0.5 bg-gray-100 text-gray-700 font-bold rounded text-xs">
                                        {{ $schedule->courseClass?->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center font-medium">
                                    {{ $schedule->courseClass?->course?->sks ?? 0 }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-700">
                                        <i class="fa-solid fa-door-open text-gray-400"></i>
                                        {{ $schedule->room?->name ?? 'TBA' }} ({{ $schedule->room?->code ?? '-' }})
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $schedule->courseClass?->academic_year ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('dosen.classes.show', $schedule->courseClass) }}" class="inline-flex items-center gap-1.5 text-xs bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold px-3 py-1.5 rounded-lg border border-blue-200 transition">
                                        <i class="fa-solid fa-users"></i> Kelola Kelas
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
