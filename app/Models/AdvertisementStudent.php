<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementStudent extends Model
{
    protected $table = 'advertisement_student';

    protected $fillable = [
        'advertisement_id',
        'student_id',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
