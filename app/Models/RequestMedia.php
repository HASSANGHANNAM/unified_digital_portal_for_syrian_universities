<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestMedia extends Model
{
    use HasFactory;

    protected $table = 'request_media';

    protected $fillable = ['name', 'type', 'path', 'request_id'];

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id', 'id');
    }
}
