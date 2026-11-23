@extends('layouts.app')
@section('title', 'Nilai')

@section('content')
    <x-admin.page-header title="Nilai Mahasiswa" subtitle="Hasil penilaian untuk mata kuliah yang telah disetujui.">
        @if ($ipk !== null)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-2 text-right">
                <p class="text-[11px] text-gray-500 uppercase">IPK ({{ $totalSks }} SKS)</p>
                <p class="text-xl font-bold text-blue-900">{{ number_format($ipk, 2) }}</p>
            </div>
        @endif
    </x-admin.page-header>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">Mata Kuliah</th><th>Tahun Akademik</th><th>SKS</th><th>Tugas</th><th>UTS</th><th>UAS</th><th>Nilai Akhir</th><th>Huruf</th></tr>
            </thead>
            <tbody>
                @forelse ($grades as $item)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $item->course_name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $item->course_code }}</p>
                        </td>
                        <td>{{ $item->academic_year }}</td>
                        <td>{{ $item->sks }}</td>
                        <td>{{ $item->assignment_score ?? '-' }}</td>
                        <td>{{ $item->mid_score ?? '-' }}</td>
                        <td>{{ $item->final_score ?? '-' }}</td>
                        <td class="font-semibold">{{ $item->final_grade ?? '-' }}</td>
                        <td><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $item->grade_letter ?? '-' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada data nilai yang tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="text-xs text-gray-500 mt-3">Tanda (-) berarti nilai belum diinput dosen. Nilai akhir = Tugas 30% + UTS 35% + UAS 35%.</p>
@endsection
