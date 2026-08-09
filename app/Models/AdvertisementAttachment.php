<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementAttachment extends Model
{
    protected $fillable = [
        'name',
        'type',
        'path',
        'advertisement_id',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }
    public function getDownloadUrl()
    {
        return route('advertisement.download', $this->id);
    }
}
