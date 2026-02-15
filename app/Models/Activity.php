<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'subject_type',
        'subject_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public static function log(string $type, string $title, ?int $userId = null, ?Model $subject = null, array $meta = []): self
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'type' => $type,
            'title' => $title,
            'subject_type' => $subject ? $subject->getMorphClass() : null,
            'subject_id' => $subject?->getKey(),
            'meta' => $meta ?: null,
        ]);
    }
}
