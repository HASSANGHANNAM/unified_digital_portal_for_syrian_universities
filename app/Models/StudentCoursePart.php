<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCoursePart extends Model
{
    use HasFactory;

    protected $table = 'student_course_parts';

    protected $fillable = ['student_course_id', 'course_part_id', 'credits', 'published'];

    protected $casts = ['published' => 'boolean'];

    public function studentCourse()
    {
        return $this->belongsTo(StudentCourse::class);
    }

    public function coursePart()
    {
        return $this->belongsTo(CoursePart::class);
    }
}