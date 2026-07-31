<?php

namespace App\DTOs;

class TeachingAssistantCourseDTO
{
    public static function fromArray(array $data): array
    {
        return [
            'course_id' => $data['course_id'],
            'course_name' => $data['course_name'],
            'course_code' => $data['course_code'],
            'credits' => $data['credits'],
            'department_id' => $data['department_id'],
            'department_name' => $data['department_name'],
            'college_id' => $data['college_id'],
            'college_name' => $data['college_name'],
            'university_id' => $data['university_id'],
            'university_name' => $data['university_name'],
        ];
    }
}
