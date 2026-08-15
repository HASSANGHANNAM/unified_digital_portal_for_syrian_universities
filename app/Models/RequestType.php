<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    protected $table = 'request_types';

    protected $fillable = [
        'name',
        'description',
        'requires_course',
        'teacher_acceptance',
        'college_dean_acceptance',
        'department_head_acceptance',
        'student_stuff_acceptance',
        'exams_stuff_acceptance',
        'doctor_acceptance'
    ];

    protected $casts = [
        'requires_course' => 'boolean',
        'teacher_acceptance' => 'boolean',
        'college_dean_acceptance' => 'boolean',
        'department_head_acceptance' => 'boolean',
        'student_stuff_acceptance' => 'boolean',
        'exams_stuff_acceptance' => 'boolean',
        'doctor_acceptance' => 'boolean',
    ];

    // ===== العلاقات (تم الإبقاء على جميعها) =====
    public function requests()
    {
        return $this->hasMany(StudentRequest::class);
    }

    public function mediaTypes()
    {
        return $this->hasMany(RequestTypeMedia::class);
    }

    public function requestTypeMedia()
    {
        return $this->hasMany(RequestTypeMedia::class, 'request_type_id', 'id');
    }

    public function availability()
    {
        return $this->hasMany(RequestTypeAvailability::class);
    }

    // ===== الدوال الجديدة =====
    /**
     * تجلب الأدوار المطلوبة لهذا النوع من الطلبات، مرتبة حسب التسلسل الهرمي.
     *
     * @return array
     */
    public function getRequiredRoles(): array
    {
        $hierarchy = config('university.hierarchy');
        $roles = [];

        foreach ($hierarchy as $role) {
            $field = $role . '_acceptance';
            if ($this->$field) {
                $roles[] = $role;
            }
        }

        return $roles;
    }

    /**
     * Accessor لتسهيل الوصول إلى required_roles كـ property.
     *
     * @return array
     */
    public function getRequiredRolesAttribute()
    {
        return $this->getRequiredRoles();
    }

    // ===== Appends لتضمينها في JSON =====
    protected $appends = ['required_roles'];
}
