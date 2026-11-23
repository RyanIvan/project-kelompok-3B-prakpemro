@extends('layouts.dosen')

@section('title', 'Input Nilai Mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- BACK BUTTON & CLASS HEADER -->
    <div>
        <a href="{{ route('dosen.grades.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 font-semibold mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kelas Penilaian
        </a>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-mono font-bold px-2.5 py-0.5 bg-blue-100 text-blue-900 rounded">
                        {{ $courseClass->course?->code ?? '-' }}
                    </span>
                    <span class="text-xs font-bold px-2.5 py-0.5 bg-indigo-100 text-indigo-900 rounded-full">
                        Kelas {{ $courseClass->name }}
                    </span>
                    <span class="text-xs text-slate-500">
                        {{ $courseClass->academic_year }}
                    </span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900">{{ $courseClass->course?->name ?? '-' }}</h2>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $courseClass->studyProgram?->name ?? 'Semua Program Studi' }} &bull; {{ $courseClass->course?->sks ?? 0 }} SKS &bull; Total Peserta Terverifikasi: {{ $enrollments->count() }} Mahasiswa
                </p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3.5 text-xs text-blue-900">
                <p class="font-bold flex items-center gap-1.5 mb-1 text-blue-800">
                    <i class="fa-solid fa-circle-info"></i> Formula Bobot Penilaian:
                </p>
                <p class="text-[11px] leading-relaxed">
                    Tugas: <strong>30%</strong> &bull; UTS: <strong>35%</strong> &bull; UAS: <strong>35%</strong><br>
                    Nilai Akhir dan Huruf Mutu dihitung otomatis saat formulir disimpan.
                </p>
            </div>
        </div>
    </div>

    @if ($enrollments->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-sm">
            <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 text-xl">
                <i class="fa-solid fa-user-clock"></i>
            </div>
            <p class="font-bold text-slate-700 text-base">Belum Ada Mahasiswa yang Disetujui</p>
            <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">
                Hanya mahasiswa dengan status KRS "Disetujui" yang dapat diinput nilainya. Silakan validasi mahasiswa terlebih dahulu di menu Kelas Saya.
            </p>
            <div class="mt-5">
                <a href="{{ route('dosen.classes.show', $courseClass) }}" class="text-xs bg-blue-900 hover:bg-blue-800 text-white font-semibold px-4 py-2.5 rounded-xl transition inline-flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-users-gear"></i> Kelola Validasi KRS
                </a>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('dosen.grades.update', $courseClass) }}">
            @csrf

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 border-b border-slate-200 font-semibold">
                            <tr>
                                <th class="px-5 py-3.5 text-center">#</th>
                                <th class="px-5 py-3.5">NIM & Nama Mahasiswa</th>
                                <th class="px-5 py-3.5 text-center w-36">Tugas (30%)</th>
                                <th class="px-5 py-3.5 text-center w-36">UTS (35%)</th>
                                <th class="px-5 py-3.5 text-center w-36">UAS (35%)</th>
                                <th class="px-5 py-3.5 text-center w-32">Nilai Akhir</th>
                                <th class="px-5 py-3.5 text-center w-28">Huruf</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="gradesTableBody">
                            @foreach ($enrollments as $index => $enrollment)
                                @php
                                    $student = $enrollment->student;
                                    $grade = $enrollment->grade;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition grade-row" data-row-id="{{ $enrollment->id }}">
                                    <td class="px-5 py-4 text-center text-xs text-slate-400 font-medium">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-800 text-sm">{{ $student?->user?->name ?? '-' }}</div>
                                        <div class="text-xs font-mono text-slate-500">{{ $student?->nim ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <input type="number" step="0.01" min="0" max="100"
                                               name="grades[{{ $enrollment->id }}][assignment_score]"
                                               value="{{ old("grades.{$enrollment->id}.assignment_score", $grade?->assignment_score) }}"
                                               placeholder="0-100"
                                               class="score-input input-assignment w-28 text-center text-sm font-semibold border border-slate-300 rounded-lg py-1.5 px-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white">
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <input type="number" step="0.01" min="0" max="100"
                                               name="grades[{{ $enrollment->id }}][mid_score]"
                                               value="{{ old("grades.{$enrollment->id}.mid_score", $grade?->mid_score) }}"
                                               placeholder="0-100"
                                               class="score-input input-mid w-28 text-center text-sm font-semibold border border-slate-300 rounded-lg py-1.5 px-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white">
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <input type="number" step="0.01" min="0" max="100"
                                               name="grades[{{ $enrollment->id }}][final_score]"
                                               value="{{ old("grades.{$enrollment->id}.final_score", $grade?->final_score) }}"
                                               placeholder="0-100"
                                               class="score-input input-final w-28 text-center text-sm font-semibold border border-slate-300 rounded-lg py-1.5 px-2 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white">
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="final-score-display text-sm font-bold text-slate-800">
                                            {{ $grade?->final_grade !== null ? number_format($grade->final_grade, 2) : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="grade-letter-display inline-block text-xs font-bold px-2 py-0.5 rounded {{ $grade?->grade_letter ? 'bg-blue-100 text-blue-800' : 'text-slate-400' }}">
                                            {{ $grade?->grade_letter ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        Pastikan semua nilai mahasiswa telah dimasukkan dengan teliti sebelum menekan tombol simpan.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dosen.grades.index') }}" class="text-xs font-semibold px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="text-xs font-bold px-6 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white shadow-sm transition inline-flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Nilai Mahasiswa
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <script>
            // Live client-side calculation preview
            document.addEventListener('DOMContentLoaded', function () {
                const rows = document.querySelectorAll('.grade-row');

                function getGradeLetter(score) {
                    if (score === null || isNaN(score)) return '-';
                    if (score >= 85) return 'A';
                    if (score >= 80) return 'A-';
                    if (score >= 75) return 'B+';
                    if (score >= 70) return 'B';
                    if (score >= 65) return 'C+';
                    if (score >= 60) return 'C';
                    if (score >= 50) return 'D';
                    return 'E';
                }

                rows.forEach(row => {
                    const assignInput = row.querySelector('.input-assignment');
                    const midInput = row.querySelector('.input-mid');
                    const finalInput = row.querySelector('.input-final');
                    const finalDisplay = row.querySelector('.final-score-display');
                    const letterDisplay = row.querySelector('.grade-letter-display');

                    function recalculate() {
                        const aVal = assignInput.value.trim();
                        const mVal = midInput.value.trim();
                        const fVal = finalInput.value.trim();

                        if (aVal === '' && mVal === '' && fVal === '') {
                            finalDisplay.textContent = '-';
                            letterDisplay.textContent = '-';
                            letterDisplay.className = 'grade-letter-display inline-block text-xs font-bold px-2 py-0.5 rounded text-slate-400';
                            return;
                        }

                        const a = parseFloat(aVal) || 0;
                        const m = parseFloat(mVal) || 0;
                        const f = parseFloat(fVal) || 0;

                        const finalScore = Math.round(((a * 0.30) + (m * 0.35) + (f * 0.35)) * 100) / 100;
                        const letter = getGradeLetter(finalScore);

                        finalDisplay.textContent = finalScore.toFixed(2);
                        letterDisplay.textContent = letter;
                        letterDisplay.className = 'grade-letter-display inline-block text-xs font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800';
                    }

                    [assignInput, midInput, finalInput].forEach(inp => {
                        inp.addEventListener('input', recalculate);
                    });
                });
            });
        </script>
    @endif
</div>
@endsection
