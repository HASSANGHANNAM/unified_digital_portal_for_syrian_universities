<?php

namespace App\DTOs;

use App\Models\CollegeFile;

class CollegeFileDTO
{
    public static function fromModel(CollegeFile $file): array
    {
        return [
            'id'          => $file->id,
            'name'        => $file->name,
            'type'        => $file->type,
            'student_year'        => $file->student_year,
            'upload_year' => $file->upload_year,
            'student_semester'    => $file->student_semester,
            'college_id'  => $file->college_id,
            'url'         => '/api/V1/college-files/view/' . $file->id,
            'created_at'  => $file->created_at->toDateTimeString(),
            'uploaded_by' => [
                'id'   => $file->uploader?->id,
                'name' => $file->uploader?->person?->full_name ?? 'غير معروف',
            ],
        ];
    }
}
