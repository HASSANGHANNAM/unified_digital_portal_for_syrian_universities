<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;


    protected $table = 'groups';

    protected $fillable = [ 'name'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_group', 'group_id', 'student_id');
    }

    public function studentGroups()
    {
        return $this->hasMany(StudentGroup::class, 'group_id', 'group_id');
    }

    public function scheduleGroups()
    {
        return $this->hasMany(ScheduleGroup::class, 'group_id', 'group_id');
    }
}
