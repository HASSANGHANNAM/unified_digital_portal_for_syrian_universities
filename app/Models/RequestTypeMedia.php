<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestTypeMedia extends Model
{
    use HasFactory;

    protected $table = 'request_type_media';

    protected $fillable = ['name', 'type', 'request_type_id'];

    public function requestType()
    {
        return $this->belongsTo(RequestType::class);
    }
}