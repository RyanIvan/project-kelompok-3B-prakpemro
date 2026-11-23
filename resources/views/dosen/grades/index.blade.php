@extends('layouts.dosen')

@section('title', 'Penilaian Mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- HEADER & SEARCH -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Penilaian Mahasiswa</h2>
            <p class="text-xs text-slate-500 mt-1">Pilih kelas perkuliahan untuk menginput atau memperbarui nilai mahasiswa.</p>
        </div>

        <form method="GET" action="{{ route('dosen.grades.index') }}" class="flex flex-wrap items-center gap-2">
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
                <a href="{{ route('dosen.grades.index') }}" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-lg transition font-medium">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- TABEL KELAS & STATUS PENILAIAN -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if ($classes->isEmpty())
            <div class="py-16 text-center text-slate-500">
                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 text-xl">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <p class="font-bold text-slate-700 text-sm">Tidak ada kelas yang ditemukan</p>
                <p class="text-xs text-slate-400 mt-1">Belum ada kelas yang diampu atau kriteria filter tidak cocok.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 border-b border-slate-200 font-semibold">
                        <tr>
                            <th class="px-5 py-3.5">Mata Kuliah & Kode</th>
                            <th class="px-5 py-3.5 text-center">Kelas</th>
                            <th class="px-5 py-3.5 text-center">SKS</th>
                            <th class="px-5 py-3.5">Tahun Ajaran</th>
                            <th class="px-5 py-3.5">Progres Penilaian</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($classes as $item)
                            @php
                                $total = $item->approved_students_count;
                                $graded = $item->graded_students_count;
                                $percent = $total > 0 ? round(($graded / $total) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-5 py-4">
                                    <div class="font-bold text-slate-800 text-sm">{{ $item->course?->name ?? '-' }}</div>
                                    <div class="text-xs font-mono text-slate-400">{{ $item->course?->code ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-800 font-bold rounded text-xs">
                                        {{ $item->name }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center font-medium">
                                    {{ $item->course?->sks ?? 0 }}
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    {{ $item->academic_year }}
                                </td>
                                <td class="px-5 py-4 min-w-[200px]">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="text-slate-500">{{ $graded }} dari {{ $total }} mahasiswa</span>
                                        <span class="font-bold {{ $percent == 100 ? 'text-blue-700' : 'text-slate-700' }}">{{ $percent }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full transition-all duration-300 {{ $percent == 100 ? 'bg-blue-600' : 'bg-indigo-600' }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <a href="{{ route('dosen.grades.edit', $item) }}" class="inline-flex items-center gap-1.5 text-xs bg-blue-900 hover:bg-blue-800 text-white font-semibold px-3.5 py-2 rounded-xl transition shadow-sm">
                                        <i class="fa-solid fa-pen-to-square"></i> Input / Edit Nilai
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $classes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
