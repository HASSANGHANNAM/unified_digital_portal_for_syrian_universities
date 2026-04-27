<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanctionType extends Model
{
    use HasFactory;

    protected $table = 'sanction_types';

    protected $fillable = ['reason', 'name', 'years', 'months', 'days'];

    public function sanctions()
    {
        return $this->hasMany(Sanction::class);
    }
}