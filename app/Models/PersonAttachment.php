<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonAttachment extends Model
{
    use HasFactory;

    protected $table = 'person_attachments';

    protected $fillable = ['name', 'path', 'person_id'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}