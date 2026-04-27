<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingAssistant extends Model
{
    use HasFactory;

    protected $table = 'teaching_assistants';

    protected $fillable = [
        'ta_id_number', 'department_id', 'supervisor_id',
        'assignment_date', 'person_id'
    ];

    protected $casts = ['assignment_date' => 'date'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Staff::class, 'supervisor_id');
    }

    public function courseStaff()
    {
        return $this->hasMany(CourseStaff::class, 'ta_id');
    }
}