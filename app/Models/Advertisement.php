<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'message',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(AdvertisementAttachment::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'advertisement_student', 'advertisement_id', 'student_id')
            ->withTimestamps();
    }
}
