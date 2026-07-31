<?php

namespace App\DTOs;

class TeachingAssistantUniversityDTO
{
    public static function fromCollection(array $data): array
    {
        return array_map(function ($university) {
            return [
                'university_id' => $university['university_id'],
                'university_name' => $university['university_name'],
                'logo_url' => $university['logo_url'],
                'colleges' => array_values(array_map(function ($college) {
                    return [
                        'college_id' => $college['college_id'],
                        'college_name' => $college['college_name'],
                        'departments' => array_values(array_map(function ($department) {
                            return [
                                'department_id' => $department['department_id'],
                                'department_name' => $department['department_name'],
                            ];
                        }, $college['departments'])),
                    ];
                }, $university['colleges'])),
            ];
        }, $data);
    }
}
