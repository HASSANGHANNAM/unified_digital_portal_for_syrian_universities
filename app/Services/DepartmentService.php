<?php

namespace App\Services;

use App\DTOs\DepartmentListDTO;
use App\DTOs\UniversityWithCollegesDTO;
use App\Models\College;
use App\Models\CollegeDean;
use App\Models\Department;
use App\Models\University;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Repositories\Contracts\UniversityRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class DepartmentService
{

    public function __construct(
        private DepartmentRepositoryInterface $repository,
        private UniversityRepositoryInterface $universityrepository,
        private MediaService $mediaService

    ) {}
    public function getDepartments(array $filters): array
    {
        $departments = $this->repository->getDepartments($filters);

        $data = $departments->map(fn($dept) => DepartmentListDTO::fromModel($dept)->toArray())
            ->values()
            ->toArray();

        return [
            'data' => $data,
            'message' => 'قائمة الأقسام.',
            'code' => 200,
        ];
    }

    public function getUniversities(array $filters): array
    {
        $universities = $this->universityrepository->getUniversitiesWithColleges($filters);

        $data = $universities->map(fn($uni) => UniversityWithCollegesDTO::fromModel($uni)->toArray())
            ->values()
            ->toArray();

        return [
            'data' => $data,
            'message' => 'قائمة الجامعات مع كلياتها.',
            'code' => 200,
        ];
    }
    public function updateUniversity(int $id, array $validated): array
    {
        $university = $this->repository->findById($id);
        if (!$university) {
            return [
                'data' => [],
                'message' => 'الجامعة غير موجودة.',
                'code' => 404,
            ];
        }
        $data = [];
        if (isset($validated['address'])) {
            $data['address'] = $validated['address'];
        }
        if (isset($validated['logo']) && $validated['logo'] instanceof UploadedFile) {
            try {
                $extracted = $this->mediaService->extractUploadedFile($validated['logo']);
                if ($university->logo_path && Storage::disk('local')->exists($university->logo_path)) {
                    Storage::disk('local')->delete($university->logo_path);
                }
                $uuid = (string) Str::uuid();
                $filename = $uuid . '.' . $extracted['extension'];
                $path = 'private/logos/universities/' . $university->id . '/' . $filename;
                Storage::disk('local')->put($path, $extracted['binary']);

                // 2.4 التحقق من نجاح الحفظ
                if (!Storage::disk('local')->exists($path)) {
                    return [
                        'data' => null,
                        'message' => 'فشل حفظ ملف الشعار على الخادم.',
                        'code' => 500,
                    ];
                }

                $data['logo_path'] = $path;
            } catch (Throwable $th) {
                return [
                    'data' => null,
                    'message' => 'حدث خطأ أثناء معالجة ملف الشعار: ' . $th->getMessage(),
                    'code' => 500,
                ];
            }
        }
        if (empty($data)) {
            return [
                'data' => [],
                'message' => 'لم يتم إرسال أي بيانات للتحديث.',
                'code' => 400,
            ];
        }
        $updated = $this->repository->update($university, $data);
        if (!$updated) {
            return [
                'data' => null,
                'message' => 'فشل تحديث بيانات الجامعة.',
                'code' => 500,
            ];
        }
        $updatedUniversity = $this->repository->findById($id);
        return [
            'data' => $updatedUniversity->only(['id', 'name', 'address', 'logo_path']),
            'message' => 'تم تحديث بيانات الجامعة بنجاح.',
            'code' => 200,
        ];
    }
    public function store(array $request, int $collegeId): array
    {
        $college = College::find($collegeId);
        if (!$college) {
            return [
                'data'   => [],
                'message' => 'الكلية غير موجودة.',
                'code'   => 404,
            ];
        }
        $dean = CollegeDean::create([
            'college_id' => $collegeId,
            'doctor_id'  => $request['doctor_id'],
            'hired_date' => $request['hired_dat'],
            'expire_date' => null,
        ]);

        return [
            'data'   => $dean,
            'message' => 'تم تعيين العميد للكلية بنجاح.',
            'code'   => 201,
        ];
    }
    public function storeDepartmentHeads(int $departmentId, array $validated): array
    {
        $department = Department::find($departmentId);
        if (!$department) {
            return [
                'data' => [],
                'message' => 'القسم غير موجود.',
                'code' => 404,
            ];
        }
        $data = [
            'department_id' => $departmentId,
            'doctor_id'     => $validated['doctor_id'],
            'hired_date'    => Carbon::now()->toDateString(),
            'expire_date'   => $validated['expire_date'] ?? null,
        ];
        $head = $this->repository->create($data);
        return [
            'data'    => $head,
            'message' => 'تم تعيين رئيس القسم بنجاح.',
            'code'    => 201,
        ];
    }
}
