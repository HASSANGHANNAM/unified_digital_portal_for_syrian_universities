<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentHead extends Model
{
    use HasFactory;

    protected $table = 'department_heads';

    protected $fillable = ['department_id', 'doctor_id', 'hired_date', 'expire_date'];

    protected $casts = [
        'hired_date' => 'date',
        'expire_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}