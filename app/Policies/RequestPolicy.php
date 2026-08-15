<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RequestPolicy
{
    public function cancel(User $user, Request $request): bool
    {
        // ensure relations are loaded like TokenDataTrait does
        $user->loadMissing(['person.student']);
        $student = $user->person?->student;
        if (!$student) {
            return false;
        }
        $studentId = $student->student_id ?? $student->id ?? null;
        if ($studentId === null) {
            return false;
        }
        return ((string) $request->student_id === (string) $studentId);
    }
    public function viewCollegeRequests(User $user, int $collegeId,  string $role): bool
    {
        if (in_array($role, ['exams_stuff', 'student_stuff'])) {
            if (!$user->person_id) {
                return false;
            }
            return $user->person->staff()
                ->whereHas('department', function ($q) use ($collegeId) {
                    $q->where('college_id', $collegeId);
                })
                ->exists();
        } elseif (in_array($role, ['doctor'])) {
            if (!$user->person_id) {
                return false;
            }
            return $user->person->doctor()
                ->whereHas('department', function ($q) use ($collegeId) {
                    $q->where('college_id', $collegeId);
                })
                ->exists();
        } elseif (in_array($role, ['teacher'])) {
            if (!$user->person_id) {
                return false;
            }
            return $user->person->teachingAssistant()
                ->whereHas('department', function ($q) use ($collegeId) {
                    $q->where('college_id', $collegeId);
                })
                ->exists();
        }
        return false;
    }
    public function viewPdf(User $user, Request $request): bool
    {
        if ($user->person_id && $request->student && $request->student->person_id === $user->person_id) {
            return true;
        }
        $allowedRoles = ['exams_stuff', 'StudentAffairs'];
        $hasRole = $user->roles()->whereIn('name', $allowedRoles)->exists();
        if (!$hasRole) {
            return false;
        }

        if (!$user->person_id) {
            return false;
        }

        $studentCollegeId = $request->student?->college_id;
        if (!$studentCollegeId) {
            return false;
        }

        return $user->person->staff()
            ->whereHas('department', function ($q) use ($studentCollegeId) {
                $q->where('college_id', $studentCollegeId);
            })
            ->exists();
    }
}
