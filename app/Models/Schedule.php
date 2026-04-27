<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week', 'start_time', 'end_time',
        'location', 'semester', 'year', 'course_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}