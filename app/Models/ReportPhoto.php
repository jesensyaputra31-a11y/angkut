<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportPhoto extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'report_id',
        'uploaded_by',
        'photo_type',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
