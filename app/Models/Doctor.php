<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id_number',
        'department_id',
        'title',
        'hire_date',
        'employment_status',
        'person_id'
    ];

    protected $casts = ['hire_date' => 'date'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courseStaff()
    {
        return $this->hasMany(CourseStaff::class);
    }

    public function departmentHeads()
    {
        return $this->hasMany(DepartmentHead::class);
    }

    public function collegeDeans()
    {
        return $this->hasMany(CollegeDean::class);
    }
    // app/Models/Doctor.php


    public function courses()
    {
        return $this->hasManyThrough(Course::class, CourseStaff::class, 'doctor_id', 'id', 'id', 'course_id');
    }
}
