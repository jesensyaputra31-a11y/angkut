<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id', 'officer_id', 'assigned_by', 'assigned_at', 'completed_at', 'notes'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}