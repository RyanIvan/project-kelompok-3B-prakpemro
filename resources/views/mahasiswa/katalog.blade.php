
@extends('mahasiswa.layout')

@section('title', 'Daftar Mata Kuliah')

@section('content')

<div class="mhs-heading">
    <div>
        <p class="mhs-eyebrow">KATALOG PERKULIAHAN</p>
        <h1>Daftar Mata Kuliah</h1>
        <p>
            Pilih mata kuliah yang tersedia.
            Pengajuan harus mendapat persetujuan dosen.
        </p>
    </div>
</div>

<section class="mhs-panel">

    <div class="mhs-panel-head">
        <div>
            <h2>Mata Kuliah Tersedia</h2>
            <p>{{ $classes->count() }} kelas ditawarkan</p>
        </div>
    </div>

    <div class="mhs-table-wrap">
        <table class="mhs-table">

            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th>Semester</th>
                    <th>SKS</th>
                    <th>Dosen</th>
                    <th>Tahun Akademik</th>
                    <th>Kapasitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            @forelse($classes as $item)

                <tr>
                    <td>
                        <strong>{{ $item->course_name }}</strong>
                        <span class="mhs-muted">
                            {{ $item->course_code }}
                            · Kelas #{{ $item->class_id }}
                        </span>
                    </td>

                    <td>{{ $item->semester }}</td>

                    <td>{{ $item->sks }}</td>

                    <td>
                        {{ $item->lecturer_name ?? 'Belum ditentukan' }}
                    </td>

                    <td>{{ $item->academic_year }}</td>

                    <td>{{ $item->capacity ?? '-' }}</td>

                    <td>

                        @if($item->my_enrollment_id)

                            <span class="mhs-badge neutral">
                                {{ ucfirst($item->my_status) }}
                            </span>

                        @else

                            <form
                                action="{{ route('mahasiswa.ambil') }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin mengambil mata kuliah ini? Pengajuan tidak dapat dibatalkan sendiri.');"
                            >
                                @csrf

                                <input
                                    type="hidden"
                                    name="class_id"
                                    value="{{ $item->class_id }}"
                                >

                                <button
                                    type="submit"
                                    class="mhs-button mhs-button-sm">
                                    + Ambil
                                </button>
                            </form>

                        @endif

                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" class="mhs-empty">
                        Belum ada kelas mata kuliah yang tersedia.
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>
    </div>

</section>

<p class="mhs-help">
    Pengambilan mata kuliah yang sudah diajukan
    tidak dapat dibatalkan melalui portal mahasiswa.
</p>

@endsection
