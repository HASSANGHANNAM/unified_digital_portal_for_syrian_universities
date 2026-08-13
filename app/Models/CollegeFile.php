<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CollegeFile extends Model
{
    protected $fillable = [
        'name',
        'type',
        'path',
        'upload_year',
        'student_year',
        'student_semester',
        'college_id',
        'uploaded_by',
    ];
    protected $casts = [
        'upload_year'      => 'integer',
        'student_year'     => 'integer',
        'student_semester' => 'integer',
        'college_id'       => 'integer',
        'uploaded_by'      => 'integer',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];
    public function college()
    {
        return $this->belongsTo(College::class);
    }
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
