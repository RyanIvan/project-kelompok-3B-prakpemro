@extends('layouts.dosen')

@section('title', 'Detail Peserta Kelas')

@section('content')
<div class="space-y-6">
    <!-- BACK BUTTON & CLASS INFO HEADER -->
    <div>
        <a href="{{ route('dosen.classes.index') }}" class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-800 font-semibold mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kelas
        </a>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-mono font-bold px-2.5 py-0.5 bg-blue-100 text-blue-800 rounded">
                        {{ $courseClass->course?->code ?? '-' }}
                    </span>
                    <span class="text-xs font-bold px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full">
                        Kelas {{ $courseClass->name }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ $courseClass->academic_year }}
                    </span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $courseClass->course?->name ?? '-' }}</h2>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $courseClass->studyProgram?->name ?? 'Semua Program Studi' }} &bull; {{ $courseClass->course?->sks ?? 0 }} SKS
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('dosen.grades.edit', $courseClass) }}" class="text-xs bg-blue-900 hover:bg-blue-800 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-star"></i> Input & Kelola Nilai
                </a>
            </div>
        </div>
    </div>

    <!-- STATUS TABS & SEARCH -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('dosen.classes.show', $courseClass) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition border {{ !request('status') ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                Semua ({{ array_sum($statusCounts->toArray()) }})
            </a>
            <a href="{{ route('dosen.classes.show', [$courseClass, 'status' => 'Pending']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition border {{ request('status') === 'Pending' ? 'bg-amber-600 text-white border-amber-600' : 'bg-white text-amber-700 border-gray-200 hover:bg-amber-50' }}">
                <i class="fa-solid fa-clock mr-1"></i> Menunggu Validasi ({{ $statusCounts['Pending'] ?? 0 }})
            </a>
            <a href="{{ route('dosen.classes.show', [$courseClass, 'status' => 'Approved']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition border {{ request('status') === 'Approved' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-emerald-700 border-gray-200 hover:bg-emerald-50' }}">
                <i class="fa-solid fa-circle-check mr-1"></i> Disetujui ({{ $statusCounts['Approved'] ?? 0 }})
            </a>
            <a href="{{ route('dosen.classes.show', [$courseClass, 'status' => 'Rejected']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition border {{ request('status') === 'Rejected' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-rose-700 border-gray-200 hover:bg-rose-50' }}">
                <i class="fa-solid fa-circle-xmark mr-1"></i> Ditolak ({{ $statusCounts['Rejected'] ?? 0 }})
            </a>
        </div>

        <!-- Search -->
        <form method="GET" action="{{ route('dosen.classes.show', $courseClass) }}" class="flex items-center gap-2">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari NIM atau Nama..." class="text-xs pl-8 pr-3 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600 shadow-sm w-56">
            </div>
            <button type="submit" class="text-xs bg-gray-800 hover:bg-gray-900 text-white px-3 py-2 rounded-lg font-semibold transition">
                Cari
            </button>
            @if (request('q'))
                <a href="{{ route('dosen.classes.show', [$courseClass, 'status' => request('status')]) }}" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-lg font-medium transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- TABEL MAHASISWA -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if ($enrollments->isEmpty())
            <div class="py-16 text-center text-gray-500">
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400 text-xl">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
                <p class="font-bold text-gray-700 text-sm">Tidak ada mahasiswa pada kriteria ini</p>
                <p class="text-xs text-gray-400 mt-1">Belum ada mahasiswa yang mengajukan KRS atau hasil pencarian nihil.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-700">
                    <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500 border-b border-gray-200 font-semibold">
                        <tr>
                            <th class="px-5 py-3.5 text-center">#</th>
                            <th class="px-5 py-3.5">NIM & Nama Mahasiswa</th>
                            <th class="px-5 py-3.5">Program Studi</th>
                            <th class="px-5 py-3.5 text-center">Angkatan</th>
                            <th class="px-5 py-3.5 text-center">Status KRS</th>
                            <th class="px-5 py-3.5 text-center">Nilai Akhir</th>
                            <th class="px-5 py-3.5 text-center">Aksi Validasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($enrollments as $index => $enrollment)
                            @php
                                $student = $enrollment->student;
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-4 text-center text-xs text-gray-400 font-medium">
                                    {{ $enrollments->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-gray-800 text-sm">{{ $student?->user?->name ?? '-' }}</div>
                                    <div class="text-xs font-mono text-gray-500">{{ $student?->nim ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-600">
                                    {{ $student?->studyProgram?->name ?? ($student?->study_program ?? '-') }}
                                </td>
                                <td class="px-5 py-4 text-center text-xs text-gray-600">
                                    {{ $student?->batch ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if ($enrollment->status === 'Approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-semibold">
                                            <i class="fa-solid fa-circle-check text-[10px]"></i> Disetujui
                                        </span>
                                    @elseif ($enrollment->status === 'Pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-xs font-semibold">
                                            <i class="fa-solid fa-clock text-[10px]"></i> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-xs font-semibold">
                                            <i class="fa-solid fa-circle-xmark text-[10px]"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if ($enrollment->grade?->final_grade !== null)
                                        <span class="font-bold text-gray-800 text-sm">{{ $enrollment->grade->final_grade }}</span>
                                        <span class="ml-1 text-xs px-1.5 py-0.5 rounded font-bold bg-gray-100 text-gray-700">{{ $enrollment->grade->grade_letter ?? '-' }}</span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum ada</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if ($enrollment->status !== 'Approved')
                                            <form method="POST" action="{{ route('dosen.enrollments.update-status', $enrollment) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Approved">
                                                <button type="submit" class="text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 font-semibold px-2.5 py-1.5 rounded-lg transition inline-flex items-center gap-1" title="Setujui KRS">
                                                    <i class="fa-solid fa-check"></i> Setujui
                                                </button>
                                            </form>
                                        @endif

                                        @if ($enrollment->status !== 'Rejected')
                                            <form method="POST" action="{{ route('dosen.enrollments.update-status', $enrollment) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="submit" onclick="return confirm('Tolak pengajuan KRS mahasiswa ini?')" class="text-xs bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-semibold px-2.5 py-1.5 rounded-lg transition inline-flex items-center gap-1" title="Tolak KRS">
                                                    <i class="fa-solid fa-xmark"></i> Tolak
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
