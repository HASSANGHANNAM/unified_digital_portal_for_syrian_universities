<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = ['title', 'message', 'sent_date', 'is_read', 'type', 'account_id'];

    protected $casts = [
        'sent_date' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'account_id');
    }
}