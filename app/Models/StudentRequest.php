<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'request_type_id', 'reason', 'submission_date',
        'decision_date', 'decision_reason',
        'student_id', 'processed_by_staff_id', 'course_id'
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'decision_date' => 'datetime',
    ];

    public function requestType()
    {
        return $this->belongsTo(RequestType::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(Staff::class, 'processed_by_staff_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function media()
    {
        return $this->hasMany(RequestMedia::class);
    }
}