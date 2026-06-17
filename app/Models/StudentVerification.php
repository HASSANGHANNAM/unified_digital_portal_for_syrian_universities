<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'reviewed_by',
        'status',
        'notes',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
