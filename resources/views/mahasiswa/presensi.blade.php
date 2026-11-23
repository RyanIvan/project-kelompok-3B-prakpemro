
@extends('mahasiswa.layout')

@section('title', 'Presensi Mahasiswa')

@section('content')

<div class="mhs-heading">
    <div>
        <p class="mhs-eyebrow">KEHADIRAN MAHASISWA</p>
        <h1>Riwayat Presensi</h1>
        <p>
            Pantau kehadiran mahasiswa dalam
            kegiatan perkuliahan.
        </p>
    </div>
</div>

@if(!$presensiAvailable)

    <div class="mhs-flash mhs-flash-danger">
        Fitur presensi belum tersedia karena tabel
        <strong>attendances</strong>
        belum dibuat di database kelompok.
    </div>

@endif

<section class="mhs-panel">

    <div class="mhs-panel-head">
        <div>
            <h2>Daftar Kehadiran</h2>
            <p>Riwayat presensi per mata kuliah</p>
        </div>
    </div>

    <div class="mhs-table-wrap">

        <table class="mhs-table">

            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th>Pertemuan</th>
                    <th>Tanggal</th>
                    <th>Status Kehadiran</th>
                </tr>
            </thead>

            <tbody>

            @forelse($attendances as $item)

                <tr>
                    <td>
                        <strong>{{ $item->course_name }}</strong>
                        <span class="mhs-muted">
                            {{ $item->course_code }}
                        </span>
                    </td>

                    <td>{{ $item->meeting_number }}</td>

                    <td>{{ $item->meeting_date }}</td>

                    <td>
                        <span class="mhs-badge neutral">
                            {{ $item->status }}
                        </span>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" class="mhs-empty">
                        Belum ada data presensi mahasiswa.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</section>

@endsection
