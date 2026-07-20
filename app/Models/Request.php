<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $table = 'requests';

    public const STATUS_PENDING = 'pending';

    // ===== حالات التجهيز (Generating) =====
    public const STATUS_GENERATING_DOCTOR = 'generating_doctor_pdf';
    public const STATUS_GENERATING_EXAMS_STUFF = 'generating_exams_stuff_pdf';
    public const STATUS_GENERATING_STUDENT_STUFF = 'generating_student_stuff_pdf';
    public const STATUS_GENERATING_DEPARTMENT_HEAD = 'generating_department_head_pdf';
    public const STATUS_GENERATING_COLLEGE_DEAN = 'generating_college_dean_pdf';
    public const STATUS_GENERATING_UNIVERSITY_DIRECTOR = 'generating_university_director_pdf';

    // ===== حالات الانتظار (Waiting) =====
    public const STATUS_WAITING_DOCTOR = 'waiting_doctor';
    public const STATUS_WAITING_EXAMS_STUFF = 'waiting_exams_stuff';
    public const STATUS_WAITING_STUDENT_STUFF = 'waiting_student_stuff';
    public const STATUS_WAITING_DEPARTMENT_HEAD = 'waiting_department_head';
    public const STATUS_WAITING_COLLEGE_DEAN = 'waiting_college_dean';
    public const STATUS_WAITING_UNIVERSITY_DIRECTOR = 'waiting_university_director';

    // ===== الحالات النهائية =====
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'request_type_id',
        'reason',
        'submission_date',
        'status',
        'decision_date',
        'decision_reason',
        'student_id',
        'processed_by_staff_id',
        'course_id',
        'pdf_path'
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'decision_date' => 'datetime',
    ];

    public function requestType()
    {
        return $this->belongsTo(RequestType::class, 'request_type_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function processedBy()
    {
        return $this->belongsTo(Staff::class, 'processed_by_staff_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function media()
    {
        return $this->hasMany(RequestMedia::class, 'request_id', 'id');
    }
    /**
     * Get all request-user records for this request.
     */
    public function requestUsers()
    {
        return $this->hasMany(RequestUser::class);
    }

    /**
     * Get all users who signed this request.
     */
    public function signers()
    {
        return $this->belongsToMany(User::class, 'request_user')
            ->withPivot('role', 'status', 'signed_at')
            ->withTimestamps();
    }

    /**
     * Get all approved signatures for this request.
     */
    public function approvers()
    {
        return $this->requestUsers()->where('status', 'approved');
    }

    /**
     * Get all rejected signatures for this request.
     */
    public function rejecters()
    {
        return $this->requestUsers()->where('status', 'rejected');
    }

    /**
     * Get all pending signatures for this request.
     */
    public function pendingSignatures()
    {
        return $this->requestUsers()->where('status', 'pending');
    }

    /**
     * Check if the request has been fully approved (all required roles approved).
     */
    public function isFullyApproved(): bool
    {
        $requiredRoles = ['affairs', 'exams', 'dean']; // حسب نظامك
        $approvedRoles = $this->requestUsers()
            ->where('status', 'approved')
            ->pluck('role')
            ->toArray();

        return empty(array_diff($requiredRoles, $approvedRoles));
    }
    public function getDisplayStatus(): string
    {
        $finalStatuses = [
            self::STATUS_PENDING,
            self::STATUS_COMPLETED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
        ];

        if (in_array($this->status, $finalStatuses)) {
            return $this->status;
        }

        if (str_starts_with($this->status, 'generating_')) {
            return str_replace(['generating_', '_pdf'], ['waiting_', ''], $this->status);
        }

        return $this->status;
    }
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'request_user')
            ->withPivot('role', 'status', 'signed_at');
    }
}
