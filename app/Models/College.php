<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'dean_id', 'university_id'];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function dean()
    {
        return $this->belongsTo(Staff::class, 'dean_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function deansHistory()
    {
        return $this->hasMany(CollegeDean::class);
    }
}