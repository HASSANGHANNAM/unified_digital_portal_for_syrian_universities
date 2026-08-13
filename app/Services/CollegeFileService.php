<?php

namespace App\Services;

use App\DTOs\CollegeFileDTO;
use App\Models\Department;
use App\Models\Staff;
use App\Models\Student;
use App\Repositories\Contracts\CollegeFileRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CollegeFileService
{
    public function __construct(
        private CollegeFileRepositoryInterface $fileRepo
    ) {}

    public function uploadFile(array $data, $file): array
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid()->toString() . '.' . $extension;
        $folder = "college_files/{$data['college_id']}";
        Storage::disk('private')->putFileAs($folder, $file, $filename);
        $fullPath = $folder . '/' . $filename;
        $collegeFile = $this->fileRepo->create([
            'name'        => $data['name'],
            'type'        => $extension,
            'path'        => $fullPath,
            'student_year'        => $data['student_year'],
            'upload_year' => (int) date('Y'),
            'student_semester'    => $data['student_semester'],
            'college_id'  => $data['college_id'],
            'uploaded_by' => auth()->user()->id,
        ]);

        return [
            'data'    => CollegeFileDTO::fromModel($collegeFile),
            'message' => 'تم رفع الملف بنجاح',
            'code'    => 201,
        ];
    }

    public function getAvailableFilesForStudent(array $filters): array
    {
        $student = Student::where('person_id', Auth::user()->person_id)->first();

        if (!$student) {
            return [
                'data'    => [],
                'message' => 'الطالب غير موجود',
                'code'    => 404,
            ];
        }

        $perPage = $filters['per_page'] ?? 15;
        $page    = $filters['page'] ?? 1;
        $studentFilters = [
            'page'      => $page,
            'per_page'  => $perPage,
            'student_year'      => $filters['student_year'] ?? null,
            'upload_year' => $filters['upload_year'] ?? null,
            'student_semester'  => $filters['student_semester'] ?? null,
            'name'      => $filters['name'] ?? null,
        ];

        $paginator = $this->fileRepo->getFilesForStudent(
            $student->college_id,
            $student->enrollment_year,
            (int) date('Y'),
            $student->current_year,
            $student->current_semester,
            $studentFilters,
            $perPage
        );

        $items = collect($paginator->items())
            ->map(fn($file) => CollegeFileDTO::fromModel($file))
            ->values()
            ->toArray();

        return [
            'data' => [
                'data' => $items,
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
            ],
            'message' => 'تم جلب الملفات بنجاح',
            'code'    => 200,
        ];
    }
    public function getAvailableFilesForStaff(array $filters,  $college_id): array
    {
        $staff = Staff::with(['department'])->where('person_id', Auth::user()->person_id)->first();
        if (!$staff) {
            return [
                'data'    => [],
                'message' => 'الموظف غير موجود  ',
                'code'    => 404,
            ];
        }
        if ($staff->department->college_id === $college_id) {
            return [
                'data'    => [],
                'message' => 'الموظف غير مصرح له بالوصول  ',
                'code'    => 404,
            ];
        }
        $perPage = $filters['per_page'] ?? 15;
        $page    = $filters['page'] ?? 1;
        $Filters = [
            'page'      => $page,
            'per_page'  => $perPage,
            'student_year'      => $filters['student_year'] ?? null,
            'upload_year' => $filters['upload_year'] ?? null,
            'student_semester'  => $filters['student_semester'] ?? null,
            'name'      => $filters['name'] ?? null,
        ];

        $paginator = $this->fileRepo->getFilesForStaff(
            $staff->department->college_id,
            $Filters,
            $perPage
        );

        $items = collect($paginator->items())
            ->map(fn($file) => CollegeFileDTO::fromModel($file))
            ->values()
            ->toArray();

        return [
            'data' => [
                'data' => $items,
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
            ],
            'message' => 'تم جلب الملفات بنجاح',
            'code'    => 200,
        ];
    }

    public function getFileDetails(int $fileId): array
    {
        $file = \App\Models\CollegeFile::with(['uploader.person', 'college'])->find($fileId);

        if (!$file) {
            return [
                'data'    => [],
                'message' => 'الملف غير موجود',
                'code'    => 404,
            ];
        }

        return [
            'data'    => CollegeFileDTO::fromModel($file),
            'message' => 'تم جلب تفاصيل الملف بنجاح',
            'code'    => 200,
        ];
    }
}
