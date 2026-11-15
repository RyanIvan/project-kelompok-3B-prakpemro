<?php

use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Department;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeAdmin(): User
{
    return User::create([
        'name' => 'Admin Test',
        'email' => 'admin-test@siakad.test',
        'password' => 'password',
        'role' => User::ROLE_ADMIN,
    ]);
}

function makeProgram(): StudyProgram
{
    $department = Department::create(['faculty_id' => null, 'code' => 'D1', 'name' => 'Jurusan Test']);

    return StudyProgram::create(['department_id' => $department->id, 'code' => 'P1', 'name' => 'Informatika', 'degree' => 'S1']);
}

test('guest is redirected to login from admin area', function () {
    $this->get('/admin')->assertRedirect(route('login'));
});

test('non admin user gets 403 on admin area', function () {
    $student = User::create([
        'name' => 'Mhs', 'email' => 'mhs-test@siakad.test', 'password' => 'password', 'role' => User::ROLE_STUDENT,
    ]);

    $this->actingAs($student)->get('/admin')->assertForbidden();
});

test('admin can open dashboard and master data pages', function () {
    $admin = makeAdmin();

    foreach (['admin.dashboard', 'admin.faculties.index', 'admin.departments.index', 'admin.study-programs.index',
        'admin.students.index', 'admin.lecturers.index', 'admin.courses.index', 'admin.rooms.index',
        'admin.classes.index', 'admin.schedules.index', 'admin.enrollments.index'] as $routeName) {
        $this->actingAs($admin)->get(route($routeName))->assertOk();
    }
});

test('admin can log in with the login form', function () {
    makeAdmin();

    $this->post(route('login.store'), [
        'role' => 'admin', 'identity' => 'admin-test@siakad.test', 'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticated();
});

test('admin can create a student with batch and study program', function () {
    $program = makeProgram();

    $this->actingAs(makeAdmin())->post(route('admin.students.store'), [
        'name' => 'Budi', 'email' => 'budi@siakad.test', 'nim' => '2210000099', 'batch' => 2022,
        'study_program_id' => $program->id, 'status' => 'Aktif', 'password' => '',
    ])->assertRedirect(route('admin.students.index'));

    $student = Student::where('nim', '2210000099')->firstOrFail();
    expect($student->batch)->toBe(2022)
        ->and($student->user->role)->toBe(User::ROLE_STUDENT)
        ->and($student->study_program_id)->toBe($program->id);
});

test('admin can toggle student status', function () {
    $program = makeProgram();
    $user = User::create(['name' => 'S', 'email' => 's@siakad.test', 'password' => 'password', 'role' => 'mahasiswa']);
    $student = $user->student()->create([
        'nim' => '1', 'batch' => 2022, 'study_program_id' => $program->id, 'study_program' => 'S1 Informatika', 'status' => 'Aktif',
    ]);

    $this->actingAs(makeAdmin())->patch(route('admin.students.toggle-status', $student))->assertRedirect();

    expect($student->fresh()->status)->toBe('Non-Aktif');
});

test('schedule that clashes in the same room is rejected', function () {
    $lecturerUser = User::create(['name' => 'Dosen', 'email' => 'd@siakad.test', 'password' => 'password', 'role' => 'dosen']);
    $lecturer = $lecturerUser->lecturer()->create(['nip' => '123']);
    $course = Course::create(['code' => 'C1', 'name' => 'MK 1', 'sks' => 3, 'semester' => 1]);
    $classA = CourseClass::create(['course_id' => $course->id, 'lecturer_id' => $lecturer->id, 'name' => 'A', 'academic_year' => '2025/2026 Genap', 'capacity' => 30]);
    $classB = CourseClass::create(['course_id' => $course->id, 'lecturer_id' => $lecturer->id, 'name' => 'B', 'academic_year' => '2025/2026 Genap', 'capacity' => 30]);
    $room = Room::create(['code' => 'R1', 'name' => 'Ruang 1', 'capacity' => 40]);

    Schedule::create(['class_id' => $classA->id, 'room_id' => $room->id, 'day' => 'Senin', 'start_time' => '08:00', 'end_time' => '10:00']);

    $this->actingAs(makeAdmin())
        ->post(route('admin.schedules.store'), [
            'class_id' => $classB->id, 'room_id' => $room->id, 'day' => 'Senin', 'start_time' => '09:00', 'end_time' => '11:00',
        ])
        ->assertSessionHasErrors('room_id');

    expect(Schedule::count())->toBe(1);
});
