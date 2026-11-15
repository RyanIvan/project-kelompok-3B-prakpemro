@extends('layouts.admin')
@section('title', 'Mahasiswa')

@section('content')
    <x-admin.page-header title="Data Mahasiswa" subtitle="Kelola akun, angkatan (batch), program studi, dan status mahasiswa.">
        <x-admin.btn-add :href="route('admin.students.create')" label="Tambah Mahasiswa" />
    </x-admin.page-header>

    <x-admin.filter-bar :action="route('admin.students.index')">
        <div class="w-64">
            <x-admin.input name="q" label="Cari" :value="request('q')" placeholder="Nama / NIM / email" />
        </div>
        <div class="w-56">
            <x-admin.select name="study_program_id" label="Program Studi" :options="$programs->pluck('full_name', 'id')"
                            :value="request('study_program_id')" placeholder="Semua" />
        </div>
        <div class="w-40">
            <x-admin.select name="status" label="Status" :options="['Aktif' => 'Aktif', 'Non-Aktif' => 'Non-Aktif']"
                            :value="request('status')" placeholder="Semua" />
        </div>
    </x-admin.filter-bar>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3">NIM</th><th>Nama</th><th>Email</th><th>Program Studi</th><th>Batch</th><th>Status</th><th class="px-4 text-right">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-mono text-xs">{{ $student->nim }}</td>
                        <td class="font-medium">{{ $student->user->name }}</td>
                        <td class="text-gray-500">{{ $student->user->email }}</td>
                        <td>{{ $student->studyProgram?->full_name ?? $student->study_program }}</td>
                        <td>{{ $student->batch }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.students.toggle-status', $student) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Klik untuk mengubah status"
                                        class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $student->status === 'Aktif' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                    {{ $student->status }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4">
                            <x-admin.row-actions :edit="route('admin.students.edit', $student)" :destroy="route('admin.students.destroy', $student)"
                                                 confirm="Hapus mahasiswa ini beserta akun dan data KRS-nya?" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Tidak ada data mahasiswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $students->links() }}</div>
@endsection
