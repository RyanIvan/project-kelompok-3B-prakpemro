
@extends('mahasiswa.layout')

@section('title', 'Dashboard Mahasiswa')

@section('content')

<div class="mhs-heading">
    <div>
        <p class="mhs-eyebrow">HALAMAN UTAMA</p>
        <h1>Halo, {{ auth()->user()->name }}!</h1>
        <p>Selamat datang di Portal Akademik Mahasiswa.</p>
    </div>

    <a href="{{ route('mahasiswa.katalog') }}"
       class="mhs-button">
        + Ambil Mata Kuliah
    </a>
</div>

<section class="mhs-stats">

    <div class="mhs-stat">
        <p>Total Pengajuan</p>
        <strong>{{ $totalApplied }}</strong>
        <small>Semua mata kuliah yang diajukan</small>
    </div>

    <div class="mhs-stat">
        <p>Kelas Disetujui</p>
        <strong>{{ $totalApproved }}</strong>
        <small>Telah divalidasi dosen</small>
    </div>

    <div class="mhs-stat">
        <p>Menunggu Validasi</p>
        <strong>{{ $totalPending }}</strong>
        <small>Menunggu persetujuan dosen</small>
    </div>

    <div class="mhs-stat">
        <p>Total SKS</p>
        <strong>{{ $totalSks }}</strong>
        <small>SKS yang telah disetujui</small>
    </div>

</section>

<div class="mhs-columns">

    <section class="mhs-panel">

        <div class="mhs-panel-head">
            <div>
                <h2>Pengajuan Mata Kuliah Terbaru</h2>
                <p>Status pengajuan KRS mahasiswa</p>
            </div>

            <a href="{{ route('mahasiswa.krs') }}"
               class="mhs-small-link">
                Lihat Semua
            </a>
        </div>

        <div class="mhs-table-wrap">
            <table class="mhs-table">
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($recent as $item)
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

                        <td>
                            <span class="mhs-badge
                                {{ $status === 'pending'
                                    ? 'waiting'
                                    : (in_array($status, ['approved', 'active', 'aktif', 'validated', 'disetujui'])
                                        ? 'success'
                                        : 'neutral') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="3" class="mhs-empty">
                            Belum ada pengajuan mata kuliah.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </section>

    <section class="mhs-panel">

        <div class="mhs-panel-head">
            <div>
                <h2>Profil Mahasiswa</h2>
                <p>Informasi akademik mahasiswa</p>
            </div>
        </div>

        <dl class="mhs-profile">

            <div>
                <dt>Nama Lengkap</dt>
                <dd>{{ auth()->user()->name }}</dd>
            </div>

            <div>
                <dt>NIM</dt>
                <dd>{{ $student->nim ?? '-' }}</dd>
            </div>

            <div>
                <dt>Email</dt>
                <dd>{{ auth()->user()->email }}</dd>
            </div>

            <div>
                <dt>Angkatan</dt>
                <dd>{{ $student->batch ?? '-' }}</dd>
            </div>

            <div>
                <dt>Program Studi</dt>
                <dd>{{ $student->study_program ?? '-' }}</dd>
            </div>

            <div>
                <dt>Status Akademik</dt>
                <dd>
                    <span class="mhs-badge
                        {{ strtolower($student->status ?? '') === 'aktif'
                            ? 'success' : 'neutral' }}">
                        {{ $student->status ?? '-' }}
                    </span>
                </dd>
            </div>

        </dl>

    </section>

</div>

@endsection
