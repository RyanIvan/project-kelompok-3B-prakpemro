<?php

use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

function acctProgram(): StudyProgram
{
    $department = Department::firstOrCreate(['code' => 'ACC'], ['name' => 'Jurusan Akun', 'faculty_id' => null]);

    return StudyProgram::firstOrCreate(['code' => 'ACC-S1'], ['department_id' => $department->id, 'name' => 'Informatika', 'degree' => 'S1']);
}

function acctAdmin(): User
{
    $user = User::create(['name' => 'Admin', 'email' => 'acc-admin@siakad.test', 'password' => 'password', 'role' => User::ROLE_ADMIN]);
    $user->admin()->create(['staff_id' => 'ADM-ACC']);

    return $user;
}

function acctStudentPayload(array $override = []): array
{
    return array_merge([
        'role' => 'mahasiswa',
        'name' => 'Mahasiswa Baru',
        'email' => 'baru@siakad.test',
        'nim' => '2310000099',
        'batch' => 2023,
        'study_program_id' => acctProgram()->id,
        'password' => 'rahasia123',
        'password_confirmation' => 'rahasia123',
    ], $override);
}

test('student can self register and the password is stored hashed', function () {
    $this->post(route('register.store'), acctStudentPayload())->assertRedirect(route('login'));

    $user = User::where('email', 'baru@siakad.test')->firstOrFail();

    expect($user->role)->toBe(User::ROLE_STUDENT)
        ->and($user->password)->not->toBe('rahasia123')
        ->and(Hash::check('rahasia123', $user->password))->toBeTrue()
        ->and($user->registered_via)->toBe('mandiri')
        ->and($user->password_changed_at)->not->toBeNull()
        ->and($user->student->nim)->toBe('2310000099');
});

test('registered student can sign in with NIM and reaches the student dashboard', function () {
    $this->post(route('register.store'), acctStudentPayload());

    $this->post(route('login.store'), ['identity' => '2310000099', 'password' => 'rahasia123'])
        ->assertRedirect(route('mahasiswa.dashboard'));

    $this->get(route('mahasiswa.dashboard'))->assertOk();
    expect(User::where('email', 'baru@siakad.test')->first()->last_login_at)->not->toBeNull();
});

test('lecturer can self register and sign in with NIP', function () {
    $this->post(route('register.store'), [
        'role' => 'dosen', 'name' => 'Dosen Baru', 'email' => 'dosenbaru@siakad.test',
        'nip' => '199001012020011001', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
    ])->assertRedirect(route('login'));

    $this->post(route('login.store'), ['identity' => '199001012020011001', 'password' => 'rahasia123'])
        ->assertRedirect(route('dosen.dashboard'));

    $this->get(route('dosen.dashboard'))->assertOk();
});

test('nobody can self register as admin', function () {
    $this->post(route('register.store'), acctStudentPayload(['role' => 'admin']))->assertSessionHasErrors('role');

    expect(User::where('role', 'admin')->count())->toBe(0);
});

test('registration rejects duplicate NIM and mismatched passwords', function () {
    $this->post(route('register.store'), acctStudentPayload());
    auth()->logout();

    $this->post(route('register.store'), acctStudentPayload(['email' => 'lain@siakad.test']))->assertSessionHasErrors('nim');
    $this->post(route('register.store'), acctStudentPayload(['email' => 'lain@siakad.test', 'nim' => '1', 'password_confirmation' => 'beda']))
        ->assertSessionHasErrors('password');
});

test('only admin can open the account monitoring page', function () {
    $this->post(route('register.store'), acctStudentPayload());
    $student = User::where('email', 'baru@siakad.test')->first();

    $this->actingAs($student)->get(route('admin.accounts.index'))->assertForbidden();

    $this->actingAs(acctAdmin())->get(route('admin.accounts.index'))
        ->assertOk()
        ->assertSee('Mahasiswa Baru')
        ->assertSee('Tersimpan terenkripsi');
});

test('admin reset gives a temporary password and invalidates the old one', function () {
    $this->post(route('register.store'), acctStudentPayload());
    $student = User::where('email', 'baru@siakad.test')->first();

    $response = $this->actingAs(acctAdmin())->post(route('admin.accounts.reset-password', $student));
    $temporary = session('reset_info')['password'];

    $response->assertSessionHas('reset_info');
    $student->refresh();
    expect(Hash::check('rahasia123', $student->password))->toBeFalse()
        ->and(Hash::check($temporary, $student->password))->toBeTrue();
});

test('deactivated account cannot sign in', function () {
    $this->post(route('register.store'), acctStudentPayload());
    $student = User::where('email', 'baru@siakad.test')->first();

    $this->actingAs(acctAdmin())->patch(route('admin.accounts.toggle-active', $student));
    auth()->logout();

    $this->post(route('login.store'), ['identity' => '2310000099', 'password' => 'rahasia123'])
        ->assertSessionHasErrors('identity');
    $this->assertGuest();
});

test('user can change their own password', function () {
    $this->post(route('register.store'), acctStudentPayload());
    $student = User::where('email', 'baru@siakad.test')->first();

    $this->actingAs($student)->put(route('account.password.update'), [
        'current_password' => 'rahasia123', 'password' => 'passwordbaru1', 'password_confirmation' => 'passwordbaru1',
    ])->assertSessionHasNoErrors();

    expect(Hash::check('passwordbaru1', $student->fresh()->password))->toBeTrue();
});

function acctClass(int $capacity = 30): CourseClass
{
    $lecturerUser = User::create(['name' => 'Dosen X', 'email' => 'dx@siakad.test', 'password' => 'password', 'role' => User::ROLE_LECTURER]);
    $lecturer = $lecturerUser->lecturer()->create(['nip' => 'NIP-X']);
    $course = Course::create(['code' => 'ACC101', 'name' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 5]);

    return CourseClass::create([
        'course_id' => $course->id, 'lecturer_id' => $lecturer->id, 'name' => 'A',
        'academic_year' => '2026/2027 Ganjil', 'capacity' => $capacity,
    ]);
}

test('student grades page shows UTS, UAS and final grade from the right columns', function () {
    $this->post(route('register.store'), acctStudentPayload());
    $user = User::where('email', 'baru@siakad.test')->first();
    $class = acctClass();

    $enrollment = Enrollment::create(['student_id' => $user->student->id, 'class_id' => $class->id, 'status' => 'Approved', 'academic_year' => $class->academic_year]);
    Grade::create(['enrollment_id' => $enrollment->id, 'assignment_score' => 81, 'mid_score' => 72, 'final_score' => 93, 'final_grade' => 82.5, 'grade_letter' => 'A-']);

    $this->actingAs($user)->get(route('mahasiswa.nilai'))
        ->assertOk()
        ->assertSeeInOrder(['81', '72', '93', '82.5', 'A-']);
});

test('student cannot take a class that is already full', function () {
    $this->post(route('register.store'), acctStudentPayload());
    $user = User::where('email', 'baru@siakad.test')->first();
    $class = acctClass(capacity: 1);

    $other = User::create(['name' => 'Lain', 'email' => 'lain2@siakad.test', 'password' => 'password', 'role' => User::ROLE_STUDENT]);
    $otherStudent = $other->student()->create(['nim' => '555', 'batch' => 2023, 'study_program' => 'X', 'status' => 'Aktif']);
    Enrollment::create(['student_id' => $otherStudent->id, 'class_id' => $class->id, 'status' => 'Pending', 'academic_year' => $class->academic_year]);

    $this->actingAs($user)->post(route('mahasiswa.ambil'), ['class_id' => $class->id])
        ->assertSessionHasErrors('class_id');

    expect(Enrollment::where('student_id', $user->student->id)->count())->toBe(0);
});
