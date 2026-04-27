<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversalCourse extends Model
{
    use HasFactory;

    protected $table = 'universal_courses';

    protected $fillable = ['name', 'image'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}