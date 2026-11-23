<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_LECTURER = 'dosen';

    public const ROLE_STUDENT = 'mahasiswa';

    protected static function booted(): void
    {
        // Catat kapan kata sandi dibuat/diubah (siapa pun yang mengubahnya) agar admin bisa memantau.
        static::saving(function (User $user) {
            if ($user->isDirty('password')) {
                $user->password_changed_at = now();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_LECTURER => 'Dosen',
            default => 'Mahasiswa',
        };
    }

    /** NIM (mahasiswa), NIP (dosen) atau ID staf (admin). */
    public function identityNumber(): ?string
    {
        return match ($this->role) {
            self::ROLE_STUDENT => $this->student?->nim,
            self::ROLE_LECTURER => $this->lecturer?->nip,
            self::ROLE_ADMIN => $this->admin?->staff_id,
            default => null,
        };
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    public function lecturer(): HasOne
    {
        return $this->hasOne(Lecturer::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }
}
