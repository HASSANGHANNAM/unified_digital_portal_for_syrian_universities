<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'submission_date', 'status', 'student_id'];

    protected $casts = ['submission_date' => 'datetime'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}