<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'notification_id';
    protected $table = 'notifications';

    // protected $fillable = ['notification_id', 'title', 'message', 'sent_date', 'is_read', 'type', 'account_id'];

    // protected $casts = [
    //     'sent_date' => 'datetime',
    //     'is_read' => 'boolean',
    // ];

}
