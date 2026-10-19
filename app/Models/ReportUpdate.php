<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportUpdate extends Model
{
    use HasFactory;

    // Biarkan true agar Laravel otomatis mengisi created_at,
    // tapi set UPDATED_AT menjadi null karena tabel tidak punya kolom updated_at.
    public $timestamps = true; 
    const UPDATED_AT = null;

    protected $fillable = [
        'report_id', 'old_status', 'new_status', 'note', 'visibility', 'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}