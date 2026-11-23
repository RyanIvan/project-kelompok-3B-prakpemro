@extends('layouts.app')
@section('title', 'Daftar Mata Kuliah')

@section('content')
    <x-admin.page-header title="Daftar Mata Kuliah" subtitle="Pilih kelas yang tersedia. Pengajuan harus mendapat persetujuan dosen." />

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3">Mata Kuliah</th><th>Smt</th><th>SKS</th><th>Dosen</th><th>Tahun Akademik</th><th>Terisi</th><th class="px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($classes as $item)
                    @php $full = $item->capacity !== null && $item->filled >= $item->capacity; @endphp
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $item->course_name }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $item->course_code }}</p>
                        </td>
                        <td>{{ $item->semester }}</td>
                        <td>{{ $item->sks }}</td>
                        <td>{{ $item->lecturer_name ?? 'Belum ditentukan' }}</td>
                        <td>{{ $item->academic_year }}</td>
                        <td>
                            <span class="{{ $full ? 'text-red-600 font-semibold' : '' }}">{{ $item->filled }}/{{ $item->capacity ?? '-' }}</span>
                        </td>
                        <td class="px-4 text-right">
                            @if ($item->my_enrollment_id)
                                <x-status-badge :status="$item->my_status" />
                            @elseif ($full)
                                <span class="text-xs font-semibold text-red-600">Penuh</span>
                            @else
                                <form action="{{ route('mahasiswa.ambil') }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin mengambil mata kuliah ini? Pengajuan tidak dapat dibatalkan sendiri.');">
                                    @csrf
                                    <input type="hidden" name="class_id" value="{{ $item->class_id }}">
                                    <button type="submit" class="inline-flex items-center gap-1.5 bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold px-3 py-2 rounded-lg shadow transition">
                                        <i class="fa-solid fa-plus text-[10px]"></i> Ambil
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada kelas mata kuliah yang tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <p class="text-xs text-gray-500 mt-3">Pengajuan yang sudah dikirim tidak dapat dibatalkan lewat portal mahasiswa.</p>
@endsection
