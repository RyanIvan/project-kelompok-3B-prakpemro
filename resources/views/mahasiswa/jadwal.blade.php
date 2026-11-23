
@extends('mahasiswa.layout')

@section('title', 'Jadwal Kuliah')

@section('content')

<div class="mhs-heading">
    <div>
        <p class="mhs-eyebrow">AGENDA AKADEMIK</p>
        <h1>Jadwal Kuliah</h1>
        <p>
            Jadwal mata kuliah yang sudah mendapat
            persetujuan dosen.
        </p>
    </div>
</div>

<section class="mhs-panel">

    <div class="mhs-panel-head">
        <div>
            <h2>Jadwal Perkuliahan</h2>
            <p>Informasi kelas dan ruang kuliah</p>
        </div>
    </div>

    <div class="mhs-table-wrap">
        <table class="mhs-table">

            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mata Kuliah</th>
                    <th>Ruangan</th>
                    <th>Tahun Akademik</th>
                </tr>
            </thead>

            <tbody>
            @forelse($schedules as $item)

                <tr>
                    <td>
                        <strong>{{ $item->day }}</strong>
                    </td>

                    <td>
                        {{ $item->start_time }}
                        -
                        {{ $item->end_time }}
                    </td>

                    <td>
                        <strong>{{ $item->course_name }}</strong>
                        <span class="mhs-muted">
                            {{ $item->course_code }}
                        </span>
                    </td>

                    <td>
                        {{ $item->room_name ?? $item->room_code ?? '-' }}
                    </td>

                    <td>{{ $item->academic_year }}</td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" class="mhs-empty">
                        Belum ada jadwal perkuliahan yang disetujui.
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>

</section>

@endsection
