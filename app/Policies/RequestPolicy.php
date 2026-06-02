<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;

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
}
