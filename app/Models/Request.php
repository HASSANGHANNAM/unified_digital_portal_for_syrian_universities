<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;


    protected $table = 'requests';

    protected $fillable = [
         'request_type_id', 'reason', 'submission_date',
        'decision_date', 'decision_reason', 'student_id',
        'processed_by_staff_id', 'course_id'
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'decision_date' => 'datetime',
    ];

    public function requestType()
    {
        return $this->belongsTo(RequestType::class, 'request_type_id', 'request_type_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(Staff::class, 'processed_by_staff_id', 'staff_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function media()
    {
        return $this->hasMany(RequestMedia::class, 'request_id', 'request_id');
    }
}
