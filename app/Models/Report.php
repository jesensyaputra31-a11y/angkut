<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_code', 'user_id', 'reporter_phone', 'category_id', 'description', 'address', 
        'latitude', 'longitude', 'status', 'priority', 'duplicate_of_report_id', 'public_approved_at'
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function photos() { return $this->hasMany(ReportPhoto::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function assignments() { return $this->hasMany(Assignment::class); }
    public function updates() { return $this->hasMany(ReportUpdate::class)->orderBy('created_at', 'desc'); }
}