<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = [
        'user_id','student_code','full_name','gender','dob','is_active'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'parent_student',
            'student_id',
            'parent_user_id'
        )->withPivot('relationship')->withTimestamps();
    }
}
