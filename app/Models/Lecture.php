<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'file_url', 'upload_date',
        'type', 'order_index', 'course_id'
    ];

    protected $casts = ['upload_date' => 'datetime'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}