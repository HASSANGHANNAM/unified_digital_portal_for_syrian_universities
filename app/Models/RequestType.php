<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    protected $table = 'request_types';

    protected $fillable = [
        'name',
        'description',
        'university_director_acceptance',
        'college_dean_acceptance',
        'department_head_acceptance',
        'student_stuff_acceptance',
        'exams_stuff_acceptance',
        'doctor_acceptance'
    ];

    protected $casts = [
        'university_director_acceptance' => 'boolean',
        'college_dean_acceptance' => 'boolean',
        'department_head_acceptance' => 'boolean',
        'student_stuff_acceptance' => 'boolean',
        'exams_stuff_acceptance' => 'boolean',
        'doctor_acceptance' => 'boolean',
    ];

    public function requests()
    {
        return $this->hasMany(StudentRequest::class);
    }

    public function mediaTypes()
    {
        return $this->hasMany(RequestTypeMedia::class);
    }

    public function requestTypeMedia()
    {
        return $this->hasMany(RequestTypeMedia::class, 'request_type_id', 'id');
    }

    public function availability()
    {
        return $this->hasMany(RequestTypeAvailability::class);
    }
}
