<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'telegram_chat_id',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Role helpers
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isTeacher(): bool { return $this->role === 'teacher'; }
    public function isStudent(): bool { return $this->role === 'student'; }
    public function isParent(): bool  { return $this->role === 'parent'; }

    // Student profile (if user is a student)
    public function studentProfile(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    // Parent -> children
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'parent_student',
            'parent_user_id',
            'student_id'
        )->withPivot('relationship')->withTimestamps();
    }

    // OTP codes (Telegram OTP)
    public function otpCodes(): HasMany
    {
        return $this->hasMany(OtpCode::class);
    }

    // PIN login (optional)
    public function pin(): HasOne
    {
        return $this->hasOne(UserPin::class);
    }

    // Teacher assignments (optional)
    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class, 'teacher_user_id');
    }
}
