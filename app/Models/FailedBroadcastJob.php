<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedBroadcastJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_ids',
        'title',
        'message',
        'type',
        'attempts',
        'last_attempt_at',
        'advertisement_id',
    ];

    protected $casts = [
        'user_ids' => 'array',
        'attempts' => 'integer',
        'last_attempt_at' => 'datetime',
    ];
}
