<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeDean extends Model
{
    use HasFactory;

    protected $table = 'college_deans';

    protected $fillable = ['college_id', 'doctor_id', 'hired_date', 'expire_date'];

    protected $casts = [
        'hired_date' => 'date',
        'expire_date' => 'date',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}