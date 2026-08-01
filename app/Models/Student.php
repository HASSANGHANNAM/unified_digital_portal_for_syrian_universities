<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id_number',
        'academic_status',
        'major',
        'enrollment_year',
        'current_year',
        'current_semester',
        'current_gpa',
        'advisor_id',
        'person_id',
        'college_id',
        'department_id'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courses()
    {
        return $this->hasMany(StudentCourse::class);
    }

    public function sanctions()
    {
        return $this->hasMany(Sanction::class);
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function requests()
    {
        return $this->hasMany(StudentRequest::class);
    }

    public function user()
    {
        // Resolve User through Person -> User relationship
        return $this->hasOneThrough(
            \App\Models\User::class,
            \App\Models\Person::class,
            'id', // Foreign key on persons table...
            'person_id', // Foreign key on users table...
            'person_id', // Local key on students table...
            'id' // Local key on persons table...
        );
    }
}
