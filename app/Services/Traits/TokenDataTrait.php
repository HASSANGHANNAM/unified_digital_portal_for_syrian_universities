<?php

namespace App\Services\Traits;

trait TokenDataTrait
{
    protected function getCurrentPerson(): ?\App\Models\Person
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }
        return $user->person ?? null;
    }

    protected function getCurrentStudent(): ?\App\Models\Student
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        $user->loadMissing(['person.student']);
        return $user->person?->student;
    }

    protected function getStudentCollegeId(): ?int
    {
        $student = $this->getCurrentStudent();
        if (!$student) {
            return null;
        }

        return $student->college_id ? (int) $student->college_id : null;
    }

    protected function getStudentName(): ?string
    {
        $person = $this->getCurrentPerson();
        if (!$person) {
            return null;
        }
        return $person->full_name ?? null;
    }

    protected function getStudentId(): ?string
    {
        $student = $this->getCurrentStudent();
        if (!$student) {
            return null;
        }
        if (isset($student->student_id)) {
            return (string) $student->student_id;
        }
        if (isset($student->id)) {
            return (string) $student->id;
        }
        return null;
    }

    protected function getStaffId(): ?string
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }
        $user->loadMissing(['person.staff']);
        $staff = $user->person?->staff;
        if (!$staff) {
            return null;
        }
        if (isset($staff->id)) {
            return (string) $staff->id;
        }
        return null;
    }
}
