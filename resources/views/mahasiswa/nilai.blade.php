
@extends('mahasiswa.layout')

@section('title', 'Nilai Akademik')

@section('content')

<div class="mhs-heading">
    <div>
        <p class="mhs-eyebrow">HASIL AKADEMIK</p>
        <h1>Nilai Mahasiswa</h1>
        <p>
            Informasi hasil penilaian untuk
            mata kuliah yang telah diikuti.
        </p>
    </div>
</div>

<section class="mhs-panel">

    <div class="mhs-panel-head">
        <div>
            <h2>Daftar Nilai</h2>
            <p>Hasil penilaian per mata kuliah</p>
        </div>
    </div>

    <div class="mhs-table-wrap">

        <table class="mhs-table">

            <thead>
                <tr>
                    <th>Mata Kuliah</th>
                    <th>Tahun Akademik</th>
                    <th>SKS</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Nilai Akhir</th>
                    <th>Huruf</th>
                </tr>
            </thead>

            <tbody>

            @forelse($grades as $item)

                <tr>
                    <td>
                        <strong>{{ $item->course_name }}</strong>
                        <span class="mhs-muted">
                            {{ $item->course_code }}
                        </span>
                    </td>

                    <td>{{ $item->academic_year }}</td>

                    <td>{{ $item->sks }}</td>

                    <td>{{ $item->assignment_score ?? '-' }}</td>

                    <td>{{ $item->mid_exam_score ?? '-' }}</td>

                    <td>{{ $item->final_exam_score ?? '-' }}</td>

                    <td>
                        <strong>
                            {{ $item->final_score ?? '-' }}
                        </strong>
                    </td>

                    <td>
                        <span class="mhs-badge neutral">
                            {{ $item->grade_letter ?? '-' }}
                        </span>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="8" class="mhs-empty">
                        Belum ada data nilai yang tersedia.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</section>

<p class="mhs-help">
    Tanda (-) menunjukkan nilai belum tersedia.
    Nilai dimasukkan melalui sistem akademik oleh dosen.
</p>

@endsection
