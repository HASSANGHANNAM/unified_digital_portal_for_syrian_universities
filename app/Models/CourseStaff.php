<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStaff extends Model
{
    use HasFactory;

    protected $table = 'course_staff';

    protected $fillable = ['course_id', 'ta_id', 'doctor_id', 'staff_id', 'is_advisor'];

    protected $casts = ['is_advisor' => 'boolean'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teachingAssistant()
    {
        return $this->belongsTo(TeachingAssistant::class, 'ta_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
