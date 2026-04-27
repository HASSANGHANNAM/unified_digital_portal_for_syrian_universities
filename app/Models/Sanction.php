<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sanction_type_id', 'status', 'issued_date', 'expiry_date',
        'notes', 'student_response', 'staff_response',
        'student_id', 'staff_id', 'course_id'
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function sanctionType()
    {
        return $this->belongsTo(SanctionType::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}