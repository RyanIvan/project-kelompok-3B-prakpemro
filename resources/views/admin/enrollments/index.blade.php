@extends('layouts.admin')
@section('title', 'Monitoring KRS')

@section('content')
    <x-admin.page-header title="Monitoring KRS" subtitle="Pantau mata kuliah yang dipilih mahasiswa. Validasi KRS dilakukan oleh dosen." />

    <div class="grid sm:grid-cols-3 gap-4 mb-4">
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4"><p class="text-xs uppercase text-amber-700 font-semibold">Pending</p><p class="text-2xl font-bold text-amber-800">{{ $counts['Pending'] ?? 0 }}</p></div>
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4"><p class="text-xs uppercase text-emerald-700 font-semibold">Disetujui</p><p class="text-2xl font-bold text-emerald-800">{{ $counts['Approved'] ?? 0 }}</p></div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4"><p class="text-xs uppercase text-red-700 font-semibold">Ditolak</p><p class="text-2xl font-bold text-red-800">{{ $counts['Rejected'] ?? 0 }}</p></div>
    </div>

    <x-admin.filter-bar :action="route('admin.enrollments.index')">
        <div class="w-64">
            <x-admin.input name="q" label="Cari mahasiswa" :value="request('q')" placeholder="Nama / NIM" />
        </div>
        <div class="w-40">
            <x-admin.select name="status" label="Status" :options="['Pending' => 'Pending', 'Approved' => 'Approved', 'Rejected' => 'Rejected']"
                            :value="request('status')" placeholder="Semua" />
        </div>
        <div class="w-56">
            <x-admin.select name="academic_year" label="Tahun Ajaran" :options="$academicYears->combine($academicYears)"
                            :value="request('academic_year')" placeholder="Semua" />
        </div>
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">NIM</th><th>Mahasiswa</th><th>Mata Kuliah</th><th>Kelas</th><th>Dosen Validator</th><th>Tahun Ajaran</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($enrollments as $enrollment)
                    @php
                        $badge = ['Pending' => 'bg-amber-100 text-amber-700', 'Approved' => 'bg-emerald-100 text-emerald-700', 'Rejected' => 'bg-red-100 text-red-700'][$enrollment->status] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-3 font-mono text-xs">{{ $enrollment->student->nim }}</td>
                        <td class="font-medium">{{ $enrollment->student->user->name }}</td>
                        <td>{{ $enrollment->courseClass->course->name }}</td>
                        <td>{{ $enrollment->courseClass->name }}</td>
                        <td>{{ $enrollment->courseClass->lecturer->user->name }}</td>
                        <td>{{ $enrollment->academic_year }}</td>
                        <td><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">{{ $enrollment->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada data KRS.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $enrollments->links() }}</div>
@endsection
