<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\PersonAttachmentRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\EmailVerificationRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\DTOs\UserDTO;
use App\DTOs\DocumentUploadDTO;


class ProfileService
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private PersonRepositoryInterface $personRepo,
        private PersonAttachmentRepositoryInterface $personAttachmentRepo,
        private EmailVerificationRepositoryInterface $emailRepo,
        private StudentRepositoryInterface $studentRepository,
    ) {}

    public function setupAccount(User $user, array $data): array
    {

        $this->userRepo->update($user, [
            'email' => $data['email'],
            'new_password' => Hash::make($data['new_password']),
        ]);

        $this->emailRepo->sendCode($user);

        $message = 'تم تحديث بياناتك بنجاح';
        $code = 200;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code
        ];
    }


    public function uploadDocument(User $user, $request): array
    {
        if (!$user->person_id) {
            throw new \Exception('يجب إكمال بياناتك أولاً');
        }

        $file = $request->file('file');

        if (!$file || !$file->isValid()) {
            throw new \Exception('الملف غير صالح أو لم يتم تحميله بشكل صحيح');
        }

        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $folder = implode('/', [
            'persons',
            $user->person_id,
            'attachments',
            date('Y'),
            date('m'),
            date('d'),
        ]);

        $path = $file->storeAs($folder, $fileName, 'public');

        $attachment = $this->personAttachmentRepo->create([
            'name' => $request->name,
            'path' => $path,
            'person_id' => $user->person_id,
        ]);

        $dto = DocumentUploadDTO::fromModel($attachment);

        return [
            'data' => $dto->toArray(),
            'message' => 'تم رفع الملف بنجاح',
            'code' => 200,
        ];
    }


    public function completeProfile(User $user, array $data): array
    {
        if ($user->person) {
            $this->personRepo->update($user->person, $data);
            $user->refresh();

            return [
                'data' => UserDTO::fromModel($user)->toArray(),
                'message' => 'تم تحديث البيانات بنجاح',
                'code' => 200
            ];
        }

        $person = $this->personRepo->create([
            'id' => (string) Str::uuid(),
            'full_name' => $data['full_name'],
            'phone' => $data['phone'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'national_number' => $data['national_number'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        $this->userRepo->update($user, [
            'person_id' => $person->id,
        ]);
        $user->refresh();

        return [
            'data' => UserDTO::fromModel($user)->toArray(),
            'message' => 'تم إكمال البيانات بنجاح',
            'code' => 200
        ];
    }

    public function submit(User $user): array
    {

        if (!$user->person_id) {
            throw new \Exception('يجب إكمال البيانات الشخصية أولاً');
        }
        $user->load('person.attachments');

        if ($user->person->attachments->isEmpty()) {
            throw new \Exception('يجب رفع الوثائق أولاً');
        }
        if ($user->status === 'pending') {
            throw new \Exception('طلبك قيد المراجعة بالفعل');
        }
        if ($user->status === 'active') {
            throw new \Exception('الحساب مفعل بالفعل');
        }

        $this->userRepo->update($user, [
            'status' => 'pending'
        ]);

        return [
            'data' => [],
            'message' => 'تم إرسال طلبك بنجاح وهو قيد المراجعة',
            'code' => 200
        ];
    }

    public function getHomePage(User $user): array
    {
        $student = $this->studentRepository->getHomePage($user->id);

        $data = [
            'fullname' => $student->person->full_name,
            'student_id_number' => $student->student_id_number,
            'current_year' => $student->current_year,
            'current_semester' => $student->current_semester,
            'status' => $student->academic_status,
            'department_name' => optional($student->department)->name,
            'college_name' => $student->college->name,
            'university_name' => $student->college->university->name,
            'gpa' => $student->current_gpa,
            'latest_request' => $student->requests->map(function ($request) {
                return [
                    'request_id' => $request->id,
                    'request_type_id' => $request->request_type_id,
                    'reason' => $request->reason,
                    'submission_date' => $request->submission_date,
                    'decision_date' => $request->decision_date,
                    'decision_reason' => $request->decision_reason,
                    'status' => $request->status,
                    'course' => $request->course ? [
                        'name' => $request->course->universalCourse->name,
                        'code' => $request->course->code,
                    ] : null,
                    'processed_by' => $request->processedBy ? [
                        'name' => $request->processedBy->person->full_name,
                    ] : null,
                ];
            })->values(),
        ];

        $message = 'تم جلب بيانات الصفحة الرئيسية بنجاح';
        $code = 200;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getAcademicProfile(User $user): array
    {
        $student = $this->studentRepository->getAcademicProfile($user->person_id);

        $data = [
            'fullname' => $student->person->full_name,
            'student_id_number' => $student->student_id_number,
            'current_year' => $student->current_year,
            'current_semester' => $student->current_semester,
            'status' => $student->academic_status,
            'department_name' => optional($student->department)->name,
            'college_name' => $student->college->name,
            'university_name' => $student->college->university->name,
            'gpa' => $student->current_gpa,

            'sanctions' => $student->sanctions->map(function ($sanction) {
                return [
                    'sanction_type_name' => $sanction->sanctionType->name,
                    'sanction_type_reason' => $sanction->sanctionType->reason,
                    'sanction_id' => $sanction->id,
                    'status' => $sanction->status,
                    'issued_date' => $sanction->issued_date,
                    'expiry_date' => $sanction->expiry_date,
                    'notes' => $sanction->notes,
                    'student_response' => $sanction->student_response,
                    'staff_response' => $sanction->staff_response,
                    'course_id' => $sanction->course_id,
                    'course_name' => optional($sanction->course?->universalCourse)->name,
                ];
            })->values(),

            'grades' => $student->courses->groupBy('course_id')->map(function ($courses) {
                    $course = $courses->first();
                    return [
                        'course_name' => $course->course->universalCourse->name,
                        'grade' => $courses->sum(function ($item) {
                            return $item->parts->sum('credits');
                        }),
                        'status' => $course->status,
                        'year' => $course->academic_year,
                        'semester' => $course->semester,
                        'date' => optional(
                            $courses->sortByDesc('created_at')->first()
                        )->created_at,
                    ];
                })->values(),
                ];

        return [
            'data' => $data,
            'message' => 'تم جلب الملف الأكاديمي بنجاح',
            'code' => 200,
        ];
    }



}
