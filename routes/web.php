<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Halaman Utama Publik (Sebelum Login)
Route::get('/', function () {
    return view('landing');
})->name('home');

// Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =====================================================================
// ADMIN  (hanya user dengan role "admin")
// =====================================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Struktur akademik: fakultas (opsional) > jurusan > program studi
        Route::resource('faculties', Admin\FacultyController::class)->except('show');
        Route::resource('departments', Admin\DepartmentController::class)->except('show');
        Route::resource('study-programs', Admin\StudyProgramController::class)->except('show');

        // Data master
        Route::patch('students/{student}/toggle-status', [Admin\StudentController::class, 'toggleStatus'])->name('students.toggle-status');
        Route::resource('students', Admin\StudentController::class)->except('show');
        Route::resource('lecturers', Admin\LecturerController::class)->except('show');
        Route::resource('courses', Admin\CourseController::class)->except('show');
        Route::resource('rooms', Admin\RoomController::class)->except('show');

        // Kelas & jadwal
        Route::resource('classes', Admin\ClassController::class)
            ->parameters(['classes' => 'courseClass'])
            ->except('show');
        Route::resource('schedules', Admin\ScheduleController::class)->except('show');

        // Monitoring KRS (read-only)
        Route::get('enrollments', [Admin\EnrollmentController::class, 'index'])->name('enrollments.index');
    });

// =====================================================================
// DOSEN & MAHASISWA: dikerjakan anggota tim lain.
// Beri nama route 'dosen.dashboard' dan 'mahasiswa.dashboard' agar
// AuthController otomatis mengarahkan user ke sana setelah login.
// =====================================================================
