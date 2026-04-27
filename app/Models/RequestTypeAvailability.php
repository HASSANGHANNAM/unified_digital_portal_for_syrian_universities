<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestTypeAvailability extends Model
{
    use HasFactory;

    protected $table = 'request_type_availability';

    protected $fillable = ['is_available', 'request_type_id', 'college_id'];

    protected $casts = ['is_available' => 'boolean'];

    public function requestType()
    {
        return $this->belongsTo(RequestType::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}