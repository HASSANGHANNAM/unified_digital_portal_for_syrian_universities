<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'universal_course_id', 'code', 'credits',
        'college_id', 'department_id'
    ];

    public function universalCourse()
    {
        return $this->belongsTo(UniversalCourse::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function parts()
    {
        return $this->hasMany(CoursePart::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function staff()
    {
        return $this->hasMany(CourseStaff::class);
    }

    public function students()
    {
        return $this->hasMany(StudentCourse::class);
    }

    public function sanctions()
    {
        return $this->hasMany(Sanction::class);
    }

    public function requests()
    {
        return $this->hasMany(StudentRequest::class);
    }
}