
@extends('mahasiswa.layout')

@section('title', 'KRS Mahasiswa')

@section('content')

<div class="mhs-heading">
    <div>
        <p class="mhs-eyebrow">KARTU RENCANA STUDI</p>
        <h1>KRS Saya</h1>
        <p>
            Daftar mata kuliah yang telah diajukan
            beserta status persetujuan dosen.
        </p>
    </div>

    <a href="{{ route('mahasiswa.katalog') }}"
       class="mhs-button">
        + Ambil Mata Kuliah
    </a>
</div>

<section class="mhs-panel">

    <div class="mhs-panel-head">
        <div>
            <h2>Daftar Pengajuan KRS</h2>
            <p>Riwayat pengambilan mata kuliah</p>
        </div>
    </div>

    <div class="mhs-table-wrap">

        <table class="mhs-table">

            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Dosen</th>
                    <th>Tahun Akademik</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status Validasi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($enrollments as $item)

                @php
                    $status = strtolower((string) $item->status);
                @endphp

                <tr>
                    <td>
                        <strong>{{ $item->course_name }}</strong>
                        <span class="mhs-muted">
                            {{ $item->course_code }}
                        </span>
                    </td>

                    <td>{{ $item->sks }}</td>

                    <td>{{ $item->lecturer_name ?? '-' }}</td>

                    <td>{{ $item->academic_year }}</td>

                    <td>{{ $item->enrolled_at ?? '-' }}</td>

                    <td>
                        @if($status === 'pending')

                            <span class="mhs-badge waiting">
                                Menunggu Validasi
                            </span>

                        @elseif(in_array($status, [
                            'approved', 'active', 'aktif',
                            'validated', 'disetujui'
                        ]))

                            <span class="mhs-badge success">
                                Disetujui
                            </span>

                        @elseif($status === 'rejected')

                            <span class="mhs-badge neutral">
                                Ditolak
                            </span>

                        @else

                            <span class="mhs-badge neutral">
                                {{ ucfirst($item->status) }}
                            </span>

                        @endif
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="6" class="mhs-empty">
                        Belum ada mata kuliah yang diajukan.
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>

</section>

<p class="mhs-help">
    Mahasiswa tidak memiliki fitur pembatalan KRS.
    Validasi pengajuan dilakukan melalui akun dosen.
</p>

@endsection
