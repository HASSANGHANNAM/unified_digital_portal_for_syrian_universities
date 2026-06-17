<?php

namespace App\Services;

use App\DTOs\DocumentUploadDTO;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\StudentVerificationRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\PersonAttachmentRepositoryInterface;

class StudentAttachmentService
{
    public function __construct(
        private PersonAttachmentRepositoryInterface $personAttachmentRepo,
        private PersonRepositoryInterface $personRepo,
        private UserRepositoryInterface $userRepo,
        private StudentVerificationRepositoryInterface $studentVerificationRepo
    ) {}

    public function getStudentAttachments(User $user,int $personId): array
    {
        if (!$user->hasRole('StudentAffairs')) {
        throw new \Exception('غير مصرح لك بالوصول');
        }

        if (!Person::find($personId)) {
            throw new \Exception('الطالب غير موجود');
        }

        $attachments = $this->personAttachmentRepo->getByPersonId($personId);

        if ($attachments->isEmpty()) {
        throw new \Exception('لا يوجد ملفات مرفوعة لهذا الطالب');
        }

        $dto = $attachments->map(function ($attachment) {
            return DocumentUploadDTO::fromModel($attachment)->toArray();
        });
        return [
            'data' => $dto,
            'message' => 'تم جلب الملفات بنجاح',
            'code' => 200,
        ];
    }

    public function reviewStudent(User $user, int $personId, array $data): array
    {
        if (!$user->hasRole('StudentAffairs')) {
            throw new \Exception('غير مصرح لك بالوصول');
        }

        return DB::transaction(function () use ($user, $personId, $data) {

            $person = $this->personRepo->findById($personId);
            if (!$person) throw new \Exception('الطالب غير موجود');

            $student = $this->userRepo->findByPersonId($personId);
            if (!$student) throw new \Exception('المستخدم غير موجود');

            if ($student->status === 'inactive') {
                throw new \Exception('الرجاء استكمال رفع بياناتك المطلوبة');
            }
            if ($student->status !== 'pending') {
                throw new \Exception('حسابك مفعل بالفعل ولا يمكن مراجعة الطلب مرة اخرى');
            }

            $this->studentVerificationRepo->create([
                'person_id'   => $personId,
                'reviewed_by' => $user->id,
                'status'      => $data['status'],
                'notes'       => $data['notes'] ?? null,
            ]);

            $this->userRepo->update($student, [
                'status' => $data['status'] === 'approved' ? 'active' : 'inactive'
            ]);

            return [
                'data'    => [],
                'message' => $data['status'] === 'approved'
                    ? ' تمت الموافقة على الطلب وتفعيل حسابك بنجاح'
                    : 'تم رفض الطلب ..الرجاء مراجعة الملاحظة المرسلة والاعادة مرة اخرى',
                'code'    => 200,
            ];
        });
}






}
