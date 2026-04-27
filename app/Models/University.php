<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'accreditation', 'university_director_id'];

    // مدير الجامعة
    public function director()
    {
        return $this->belongsTo(Staff::class, 'university_director_id');
    }

    // الكليات
    public function colleges()
    {
        return $this->hasMany(College::class);
    }
}