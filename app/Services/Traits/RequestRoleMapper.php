<?php

namespace App\Services\Traits;

trait RequestRoleMapper
{
    protected function getRoleMapping(): array
    {
        return [
            'TeachingAssistant' => 'teacher',
            'Instructor' => 'doctor',
            'Dean' => 'college_dean',
            'HeadOfDepartment' => 'department_head',
            'Examination' => 'exams_stuff',
            'StudentAffairs' => 'student_stuff',
        ];
    }

    protected function mapSpatieRolesToRequestRoles(array $spatieRoles): array
    {
        $mapping = $this->getRoleMapping();
        $requestRoles = [];

        foreach ($spatieRoles as $role) {
            if (isset($mapping[$role])) {
                $requestRoles[] = $mapping[$role];
            }
        }

        return array_unique($requestRoles);
    }

    protected function getWaitingStatusesFromSpatieRoles(array $spatieRoles): array
    {
        $requestRoles = $this->mapSpatieRolesToRequestRoles($spatieRoles);
        return array_map(fn($role) => 'waiting_' . $role, $requestRoles);
    }
}
