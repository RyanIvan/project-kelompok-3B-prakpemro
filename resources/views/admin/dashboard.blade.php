@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <x-admin.page-header title="SIAKAD Admin Dashboard" :subtitle="'Selamat datang, '.auth()->user()->name.'! Kelola data akademik universitas di sini.'" />

    <!-- Kartu statistik -->
    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        @php
            $cards = [
                ['Total Mahasiswa Aktif', $stats['students'], 'fa-user-graduate', 'bg-blue-100 text-blue-600', 'admin.students.index'],
                ['Total Dosen', $stats['lecturers'], 'fa-chalkboard-user', 'bg-emerald-100 text-emerald-600', 'admin.lecturers.index'],
                ['Mata Kuliah', $stats['courses'], 'fa-book', 'bg-purple-100 text-purple-600', 'admin.courses.index'],
                ['Kelas Berjalan', $stats['classes'], 'fa-people-group', 'bg-orange-100 text-orange-600', 'admin.classes.index'],
            ];
        @endphp
        @foreach ($cards as [$label, $value, $icon, $color, $route])
            <a href="{{ route($route) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl {{ $color }}"><i class="fa-solid {{ $icon }}"></i></div>
                <div>
                    <p class="text-xs text-gray-500">{{ $label }}</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($value, 0, ',', '.') }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Aksi cepat -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="font-bold text-gray-900 mb-3"><i class="fa-solid fa-bolt text-yellow-500 mr-1"></i> Aksi Cepat</h3>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 text-sm font-semibold">
            <a href="{{ route('admin.students.create') }}" class="rounded-xl bg-blue-100 hover:bg-blue-200 text-blue-900 p-4">+ Tambah Mahasiswa</a>
            <a href="{{ route('admin.lecturers.create') }}" class="rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-900 p-4">+ Tambah Dosen</a>
            <a href="{{ route('admin.classes.create') }}" class="rounded-xl bg-purple-100 hover:bg-purple-200 text-purple-900 p-4">+ Buat Kelas</a>
            <a href="{{ route('admin.schedules.create') }}" class="rounded-xl bg-orange-100 hover:bg-orange-200 text-orange-900 p-4">+ Atur Jadwal</a>
        </div>
    </div>

    <div class="grid xl:grid-cols-3 gap-6">
        <!-- Mahasiswa terbaru -->
        <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-gray-900">Data Mahasiswa Terbaru</h3>
                <a href="{{ route('admin.students.index') }}" class="text-sm text-blue-700 hover:underline">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-xs text-gray-500 uppercase border-b">
                        <tr><th class="py-2">NIM</th><th>Nama</th><th>Program Studi</th><th>Angkatan</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($latestStudents as $student)
                            <tr class="border-b last:border-0">
                                <td class="py-2.5 font-mono text-xs">{{ $student->nim }}</td>
                                <td>{{ $student->user->name }}</td>
                                <td>{{ $student->studyProgram?->full_name ?? $student->study_program }}</td>
                                <td>{{ $student->batch }}</td>
                                <td>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $student->status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }}">{{ $student->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-gray-400">Belum ada data mahasiswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Jadwal hari ini + KRS pending -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-gray-900 mb-3"><i class="fa-solid fa-calendar-day text-blue-600 mr-1"></i> Jadwal Hari Ini ({{ $today }})</h3>
                @forelse ($todaySchedules as $schedule)
                    <div class="border-l-4 border-blue-500 bg-blue-50 rounded-lg px-3 py-2 mb-2 text-sm">
                        <p class="text-xs text-gray-500">{{ $schedule->start_time }} - {{ $schedule->end_time }} &bull; {{ $schedule->room->name }}</p>
                        <p class="font-semibold">{{ $schedule->courseClass->label }}</p>
                        <p class="text-xs text-gray-500">{{ $schedule->courseClass->lecturer->user->name }}</p>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Tidak ada jadwal kuliah hari ini.</p>
                @endforelse
            </div>

            <a href="{{ route('admin.enrollments.index', ['status' => 'Pending']) }}" class="block bg-amber-50 border border-amber-200 rounded-xl p-5 hover:shadow-md transition">
                <p class="text-xs text-amber-700 uppercase font-semibold">KRS Menunggu Validasi Dosen</p>
                <p class="text-3xl font-bold text-amber-800">{{ $pendingEnrollments }}</p>
            </a>
        </div>
    </div>
@endsection
