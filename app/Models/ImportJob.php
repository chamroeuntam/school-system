<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportJob extends Model
{
    protected $fillable = [
        'sheet_source_id','run_by','status',
        'total_rows','success_rows','failed_rows','message'
    ];

    public function sheetSource(): BelongsTo
    {
        return $this->belongsTo(SheetSource::class);
    }

    public function runner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'run_by');
    }

    public function errors(): HasMany
    {
        return $this->hasMany(ImportJobError::class);
    }
}
