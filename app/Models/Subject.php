<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['name'];

    public function rules(): HasMany
    {
        return $this->hasMany(SubjectRule::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
