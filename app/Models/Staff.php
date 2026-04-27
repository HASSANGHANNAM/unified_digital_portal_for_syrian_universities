<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'staff_id_number', 'department_id', 'hire_date',
        'employment_status', 'person_id'
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

    public function sanctions()
    {
        return $this->hasMany(Sanction::class);
    }

    public function processedRequests()
    {
        return $this->hasMany(StudentRequest::class, 'processed_by_staff_id');
    }

    public function courseStaff()
    {
        return $this->hasMany(CourseStaff::class);
    }

    // dean of which colleges
    public function managedColleges()
    {
        return $this->hasMany(College::class, 'dean_id');
    }
}