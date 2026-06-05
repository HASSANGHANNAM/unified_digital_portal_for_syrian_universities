<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'college_id'];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function teachingAssistants()
    {
        return $this->hasMany(TeachingAssistant::class);
    }

    public function heads()
    {
        return $this->hasMany(DepartmentHead::class);
    }

    public function studyPlanCourses()
    {
        return $this->hasMany(StudyPlanCourse::class);
    }
}
