<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePart extends Model
{
    use HasFactory;

    protected $table = 'course_parts';

    protected $fillable = ['course_id', 'percentage', 'name'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function studentCourseParts()
    {
        return $this->hasMany(StudentCoursePart::class);
    }
}