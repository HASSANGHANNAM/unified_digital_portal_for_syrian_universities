<?php

namespace App\Services;

use App\DTOs\CollegeStudentDTO;
use App\DTOs\CollegeSuggestionDTO;
use App\DTOs\StudentSuggestionDTO;
use App\Repositories\Contracts\SuggestionRepositoryInterface;
use App\Repositories\StudentRepository;
use App\Services\Traits\TokenDataTrait;
use App\Models\College;
use App\Models\Department;
use App\Models\Staff;
use App\Models\University;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Exceptions\ValidationException;
use App\Exceptions\ExcelImportValidationException;
use Throwable;
use Illuminate\Support\Facades\Auth;

class StudentService
{
    use TokenDataTrait;
    public function __construct(
        private SuggestionRepositoryInterface $suggestionRepository,
        private StudentRepository $studentRepository,
    ) {}
    public function getCollegeStudents(array $validated): array
    {
        $collegeId = (int) ($validated['college_id'] ?? 0);
        $perPage = (int) ($validated['per_page'] ?? 15);
        $page = (int) ($validated['page'] ?? 1);
        $students = $this->studentRepository->getStudentsByCollege($collegeId, $perPage, $page);

        if (!$students || $students->isEmpty()) {
            return [
                'data' => [
                    [],
                    'meta' => [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => $perPage,
                        'total' => 0,
                    ],
                ],
                'message' => 'تم جلب الطلاب بنجاح',
                'code' => 200,
            ];
        }

        $items = $students->getCollection()->map(function ($student) {
            return CollegeStudentDTO::fromModel($student)->toArray();
        })->values()->all();

        return [
            'data' => [
                'students' => $items,
                'meta' => [
                    'current_page' => $students->currentPage(),
                    'last_page' => $students->lastPage(),
                    'per_page' => $students->perPage(),
                    'total' => $students->total(),
                ],
            ],
            'message' => 'تم جلب الطلاب بنجاح',
            'code' => 200,
        ];
    }
    public function getStudentSuggestions(array $validated): array
    {
        $user = auth()->user();
        $perPage = $validated['per_page'] ?? 15;
        $page    = $validated['page'] ?? 1;
        $filters = [];
        if (!empty($validated['content'])) {
            $filters['content'] = $validated['content'];
        }
        if (!empty($validated['status'])) {
            $filters['status'] = $validated['status'];
        }
        $paginator = $this->suggestionRepository->getFiltered($filters, $this->getStudentId(), $perPage, $page);
        $data = StudentSuggestionDTO::fromServiceData($paginator->items());
        return [
            'data'       => $data,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
            'message'    => 'تم جلب الاقتراحات بنجاح.',
            'code'       => 200,
        ];
    }
    public function collegeSuggestions(array $validated): array
    {
        $collegeId = $validated['college_id'] ?? null;
        if (!$collegeId) {
            return [
                'data'    => [],
                'message' => 'معرف الكلية مطلوب.',
                'code'    => 422,
            ];
        }
        $perPage = $validated['per_page'] ?? 15;
        $page    = $validated['page'] ?? 1;

        $filters = [];
        if (!empty($validated['content'])) {
            $filters['content'] = $validated['content'];
        }
        if (!empty($validated['status'])) {
            $filters['status'] = $validated['status'];
        }

        $paginator = $this->suggestionRepository->getByCollegeId($collegeId, $filters, $perPage, $page);
        $data = CollegeSuggestionDTO::fromServiceData($paginator->items());

        return [
            'data'       => $data,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
            'message'    => 'تم جلب اقتراحات الكلية بنجاح.',
            'code'       => 200,
        ];
    }
    public function addStudentSuggestion(array $validated): array
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('Student')) {
            return [
                'data'    => [],
                'message' => 'المستخدم غير مصرح له بإضافة اقتراح.',
                'code'    => 403,
            ];
        }
        $student = $this->getStudentId();
        $data = [
            'content'    => $validated['content'],
            'student_id' => $student,
            'submission_date' => NOW(),
            'status'     => 'قيد المراجعة',
        ];
        $suggestion = $this->suggestionRepository->create($data);
        $dto = StudentSuggestionDTO::fromModel($suggestion);
        return [
            'data'    => $dto,
            'message' => 'تم إضافة الاقتراح بنجاح.',
            'code'    => 201,
        ];
    }
    public function updateSuggestionStatus(array $validated): array
    {
        $id = $validated['id'] ?? null;
        $newStatus = $validated['status'] ?? null;
        if (!$id || !$newStatus) {
            return [
                'data'    => [],
                'message' => 'معرف الاقتراح والحالة مطلوبان.',
                'code'    => 422,
            ];
        }
        $suggestion = $this->suggestionRepository->updateStatus($id, $newStatus);
        if (!$suggestion) {
            return [
                'data'    => [],
                'message' => 'الاقتراح غير موجود، أو ليس في حالة "قيد المراجعة".',
                'code'    => 404,
            ];
        }
        $data = CollegeSuggestionDTO::fromModel($suggestion);

        return [
            'data'    => $data,
            'message' => 'تم تحديث حالة الاقتراح بنجاح.',
            'code'    => 200,
        ];
    }
    public function importStudents(UploadedFile $file): array
    {
        $user = Auth::user();
        if (!$user->hasRole('StudentAffairs')) {
            throw new \Exception('غير مصرح لك بالوصول');
        }

        $staff = Staff::where('person_id',$user->person_id)->first();
        if (!$staff) {
            return [
                'data' => [],
                'message' => 'Staff member not found.',
                'code' => 404,
            ];
        }
        $department = Department::find($staff->department_id);
        if (!$department) {
            return [
                'data' => [],
                'message' => 'Staff department not found.',
                'code' => 404,
            ];
        }
        $college = College::find($department->college_id);
        if (!$college) {
            return [
                'data' => [],
                'message' => 'Staff college not found.',
                'code' => 404,
            ];
        }
        $university = University::find($college->university_id);
        if (!$university) {
            return [
                'data' => [],
                'message' => 'Staff university not found.',
                'code' => 404,
            ];
        }
        try {
            $result = $this->studentRepository->importStudents(
                $file,
                $university->id,
                $college->id
            );

        } catch (ExcelImportValidationException $exception) {

            return [
                'data' => [
                    'errors' => $exception->getErrors(),
                ],
                'message' => 'Excel validation failed.',
                'code' => 422,
            ];

        } catch (ValidationException $exception) {

            return [
                'data' => [
                    'errors' => $exception->errors(),
                ],
                'message' => 'Excel validation failed.',
                'code' => 422,
            ];

        } catch (Throwable $exception) {

            return [
                'data' => [
                    'exception' => $exception->getMessage(),
                ],
                'message' => 'Failed importing students.',
                'code' => 500,
            ];
        }
        return [
            'data' => $result['report'],
            'message' => 'تم رفع بيانات الطلاب بنجاح.',
            'code' => 200,
        ];
    }
}
