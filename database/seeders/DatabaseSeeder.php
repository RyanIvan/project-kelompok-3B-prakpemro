<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Data awal untuk development. Aman dijalankan ulang (memakai firstOrCreate).
     *
     * Akun login:
     *   Admin     : admin@siakad.test   / password   (atau ID staf ADM001)
     *   Dosen     : dosen@siakad.test   / password   (atau NIP 198001012005011001)
     *   Mahasiswa : mhs@siakad.test     / password   (atau NIM 2210000001)
     */
    public function run(): void
    {
        // --- Akun admin ---
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@siakad.test'],
            ['name' => 'Administrator SIAKAD', 'password' => 'password', 'role' => User::ROLE_ADMIN]
        );
        $adminUser->admin()->firstOrCreate(
            ['staff_id' => 'ADM001'],
            ['department' => 'BAAK', 'phone' => '081200000000']
        );

        // --- Struktur akademik: fakultas (opsional) > jurusan > prodi ---
        $ft = Faculty::firstOrCreate(['code' => 'FKIP'], ['name' => 'Fakultas Keguruan dan Ilmu Pendidikan']);

        $jurusanTeknik = Department::firstOrCreate(
            ['code' => 'PTIK'],
            ['name' => 'Pendidikan Teknik Informatika dan Komputer', 'faculty_id' => $ft->id]
        );
        // Contoh jurusan TANPA fakultas
        Department::firstOrCreate(['code' => 'UMUM'], ['name' => 'Jurusan Umum', 'faculty_id' => null]);

        $prodiPtik = StudyProgram::firstOrCreate(
            ['code' => 'PTIK-S1'],
            ['department_id' => $jurusanTeknik->id, 'name' => 'Pendidikan Teknik Informatika dan Komputer', 'degree' => 'S1']
        );
        StudyProgram::firstOrCreate(
            ['code' => 'IF-S1'],
            ['department_id' => $jurusanTeknik->id, 'name' => 'Informatika', 'degree' => 'S1']
        ); // satu jurusan, beberapa program studi

        // --- Ruang ---
        $ruang = Room::firstOrCreate(['code' => 'A101'], ['name' => 'Ruang A101', 'capacity' => 40]);
        Room::firstOrCreate(['code' => 'LAB1'], ['name' => 'Laboratorium Komputer 1', 'capacity' => 30]);

        // --- Mata kuliah ---
        $pemrograman = Course::firstOrCreate(
            ['code' => 'IF101'],
            ['name' => 'Algoritma dan Pemrograman', 'sks' => 3, 'semester' => 1, 'study_program_id' => $prodiPtik->id]
        );
        $web = Course::firstOrCreate(
            ['code' => 'IF201'],
            ['name' => 'Pemrograman Web', 'sks' => 3, 'semester' => 3, 'study_program_id' => $prodiPtik->id]
        );
        $web->prerequisites()->syncWithoutDetaching([$pemrograman->id]);

        // --- Dosen ---
        $dosenUser = User::firstOrCreate(
            ['email' => 'dosen@siakad.test'],
            ['name' => 'Dr. Budi Santoso, M.Kom.', 'password' => 'password', 'role' => User::ROLE_LECTURER]
        );
        $dosen = $dosenUser->lecturer()->firstOrCreate(
            ['nip' => '198001012005011001'],
            ['nidn' => '0001018001', 'phone' => '081211112222']
        );

        // --- Mahasiswa ---
        $mhsUser = User::firstOrCreate(
            ['email' => 'mhs@siakad.test'],
            ['name' => 'Aulia Rahman', 'password' => 'password', 'role' => User::ROLE_STUDENT]
        );
        $mhsUser->student()->firstOrCreate(
            ['nim' => '2210000001'],
            [
                'batch' => 2022,
                'study_program_id' => $prodiPtik->id,
                'study_program' => $prodiPtik->full_name,
                'status' => 'Aktif',
            ]
        );

        // --- Kelas & jadwal contoh ---
        $kelas = CourseClass::firstOrCreate(
            ['course_id' => $web->id, 'name' => 'A', 'academic_year' => '2025/2026 Genap'],
            ['lecturer_id' => $dosen->id, 'study_program_id' => $prodiPtik->id, 'capacity' => 40]
        );
        Schedule::firstOrCreate(
            ['class_id' => $kelas->id, 'day' => 'Senin', 'start_time' => '08:00'],
            ['room_id' => $ruang->id, 'end_time' => '10:30']
        );
    }
}
