<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $fillable = [
        'id',
         'national_id', 'full_name', 'phone',
        'birth_date', 'national_number', 'address', 'profile_image'
    ];

    protected $casts = ['birth_date' => 'date'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function teachingAssistant()
    {
        return $this->hasOne(TeachingAssistant::class);
    }

    public function attachments()
    {
        return $this->hasMany(PersonAttachment::class);
    }

    // public function academicRecords()
    // {
    //     return $this->hasMany(StudentAcademicRecord::class);
    // }
}
