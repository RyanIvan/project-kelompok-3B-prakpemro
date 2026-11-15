<?php

use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeDosen(string $nip = '198001012005011001', string $email = 'dosen-test@siakad.test', ?string $nidn = null): array
{
    static $nidnCounter = 1000;
    $nidnCounter++;

    $user = User::create([
        'name' => 'Dr. Budi Test, M.Kom.',
        'email' => $email,
        'password' => 'password',
        'role' => User::ROLE_LECTURER,
    ]);

    $lecturer = $user->lecturer()->create([
        'nip' => $nip,
        'nidn' => $nidn ?? (string) $nidnCounter,
        'phone' => '08123456789',
    ]);

    return [$user, $lecturer];
}

function makeStudentUser(string $nim = '2210000001', string $email = 'mhs-test@siakad.test'): array
{
    $user = User::create([
        'name' => 'Mahasiswa Test',
        'email' => $email,
        'password' => 'password',
        'role' => User::ROLE_STUDENT,
    ]);

    $student = $user->student()->create([
        'nim' => $nim,
        'batch' => 2022,
        'study_program' => 'S1 Informatika',
        'status' => 'Aktif',
    ]);

    return [$user, $student];
}

test('guest is redirected to login from dosen area', function () {
    $this->get('/dosen')->assertRedirect(route('login'));
});

test('non dosen user gets 403 on dosen area', function () {
    [$studentUser] = makeStudentUser();

    $this->actingAs($studentUser)->get('/dosen')->assertForbidden();
});

test('dosen can log in via login form and gets redirected to dosen dashboard', function () {
    makeDosen(nip: '198001012005011001', email: 'dosen-login@siakad.test');

    $this->post(route('login.store'), [
        'role' => 'dosen',
        'identity' => '198001012005011001',
        'password' => 'password',
    ])->assertRedirect(route('dosen.dashboard'));

    $this->assertAuthenticated();
});

test('dosen can access dashboard, schedules, classes, and grades pages', function () {
    [$dosenUser, $lecturer] = makeDosen();

    $department = Department::create(['code' => 'IF', 'name' => 'Informatika']);
    $prodi = StudyProgram::create(['department_id' => $department->id, 'code' => 'IF-S1', 'name' => 'S1 Informatika']);
    $course = Course::create(['code' => 'IF101', 'name' => 'Pemrograman Web', 'sks' => 3, 'semester' => 3, 'study_program_id' => $prodi->id]);
    $room = Room::create(['code' => 'LAB1', 'name' => 'Lab 1', 'capacity' => 30]);

    $class = CourseClass::create([
        'course_id' => $course->id,
        'lecturer_id' => $lecturer->id,
        'study_program_id' => $prodi->id,
        'name' => 'A',
        'academic_year' => '2025/2026 Genap',
        'capacity' => 30,
    ]);

    Schedule::create([
        'class_id' => $class->id,
        'room_id' => $room->id,
        'day' => 'Senin',
        'start_time' => '08:00',
        'end_time' => '10:30',
    ]);

    foreach (['dosen.dashboard', 'dosen.schedules.index', 'dosen.classes.index', 'dosen.grades.index'] as $routeName) {
        $this->actingAs($dosenUser)->get(route($routeName))->assertOk();
    }
});

test('dosen cannot view or edit classes of another lecturer', function () {
    [$dosen1] = makeDosen('111', 'dosen1@siakad.test');
    [, $lecturer2] = makeDosen('222', 'dosen2@siakad.test');

    $course = Course::create(['code' => 'C1', 'name' => 'MK 1', 'sks' => 3, 'semester' => 1]);
    $class2 = CourseClass::create([
        'course_id' => $course->id,
        'lecturer_id' => $lecturer2->id,
        'name' => 'B',
        'academic_year' => '2025/2026 Genap',
        'capacity' => 30,
    ]);

    $this->actingAs($dosen1)->get(route('dosen.classes.show', $class2))->assertForbidden();
    $this->actingAs($dosen1)->get(route('dosen.grades.edit', $class2))->assertForbidden();
});

test('dosen can approve and reject student krs enrollment', function () {
    [$dosenUser, $lecturer] = makeDosen();
    [, $student] = makeStudentUser();

    $course = Course::create(['code' => 'IF201', 'name' => 'Basis Data', 'sks' => 3, 'semester' => 3]);
    $class = CourseClass::create([
        'course_id' => $course->id,
        'lecturer_id' => $lecturer->id,
        'name' => 'A',
        'academic_year' => '2025/2026 Genap',
        'capacity' => 30,
    ]);

    $enrollment = Enrollment::create([
        'student_id' => $student->id,
        'class_id' => $class->id,
        'status' => 'Pending',
        'academic_year' => '2025/2026 Genap',
    ]);

    // Approve
    $this->actingAs($dosenUser)
        ->patch(route('dosen.enrollments.update-status', $enrollment), ['status' => 'Approved'])
        ->assertRedirect();

    expect($enrollment->fresh()->status)->toBe('Approved');

    // Reject
    $this->actingAs($dosenUser)
        ->patch(route('dosen.enrollments.update-status', $enrollment), ['status' => 'Rejected'])
        ->assertRedirect();

    expect($enrollment->fresh()->status)->toBe('Rejected');
});

test('dosen can input and calculate student grades with letter mark', function () {
    [$dosenUser, $lecturer] = makeDosen();
    [, $student] = makeStudentUser();

    $course = Course::create(['code' => 'IF301', 'name' => 'Kecerdasan Buatan', 'sks' => 3, 'semester' => 5]);
    $class = CourseClass::create([
        'course_id' => $course->id,
        'lecturer_id' => $lecturer->id,
        'name' => 'A',
        'academic_year' => '2025/2026 Genap',
        'capacity' => 30,
    ]);

    $enrollment = Enrollment::create([
        'student_id' => $student->id,
        'class_id' => $class->id,
        'status' => 'Approved',
        'academic_year' => '2025/2026 Genap',
    ]);

    // Input scores: Tugas: 80, UTS: 90, UAS: 85
    // Final grade: (80 * 0.3) + (90 * 0.35) + (85 * 0.35) = 24 + 31.5 + 29.75 = 85.25 -> A
    $this->actingAs($dosenUser)
        ->post(route('dosen.grades.update', $class), [
            'grades' => [
                $enrollment->id => [
                    'assignment_score' => 80,
                    'mid_score' => 90,
                    'final_score' => 85,
                ],
            ],
        ])
        ->assertRedirect(route('dosen.grades.edit', $class))
        ->assertSessionHas('success');

    $grade = Grade::where('enrollment_id', $enrollment->id)->firstOrFail();
    expect((float) $grade->assignment_score)->toBe(80.0)
        ->and((float) $grade->mid_score)->toBe(90.0)
        ->and((float) $grade->final_score)->toBe(85.0)
        ->and((float) $grade->final_grade)->toBe(85.25)
        ->and($grade->grade_letter)->toBe('A');
});
