<?php

namespace App\Repositories;

use App\Models\Request;
use App\Models\RequestUser;
use App\Models\User;
use App\Models\UserSignature;
use App\Models\RequestType;
use App\Models\StudentCoursePart;
use App\Services\Traits\RequestRoleMapper;
use Illuminate\Support\Facades\Gate;
use App\Policies\RequestPolicy;
use Illuminate\Support\Facades\DB;
use App\Jobs\GenerateRequestPdfJob;
use Illuminate\Support\Facades\Config;

class RequestRepository
{
    use RequestRoleMapper;

    public function __construct(private Request $model) {}

    /**
     * التحقق مما إذا كان نوع الطلب يحتاج إلى توليد PDF.
     */
    protected function shouldGeneratePdf(Request $request): bool
    {
        $requestTypeName = $request->requestType?->name ?? '';
        $pdfTypes = Config::get('requests.pdf_required_types', []);
        return in_array($requestTypeName, $pdfTypes);
    }

    public function create(array $data): Request
    {
        $data['status'] = $data['status'] ?? Request::STATUS_PENDING;
        $data['submission_date'] = $data['submission_date'] ?? now();
        $data['decision_date'] = $data['decision_date'] ?? null;
        $data['decision_reason'] = $data['decision_reason'] ?? null;
        $data['processed_by_staff_id'] = $data['processed_by_staff_id'] ?? null;
        $data['course_id'] = $data['course_id'] ?? null;

        $request = $this->model->create($data);

        // تحميل نوع الطلب للتحقق
        $request->loadMissing('requestType');

        if ($this->shouldGeneratePdf($request)) {
            // يحتاج PDF → نطلق Job
            GenerateRequestPdfJob::dispatch($request);
        } else {
            // لا يحتاج PDF → نغير الحالة مباشرة
            $firstRole = $request->requestType?->getRequiredRoles()[0] ?? null;
            if ($firstRole) {
                $request->status = 'waiting_' . $firstRole;
            } else {
                $request->status = 'completed';
            }
            $request->save();
        }

        return $request;
    }

    public function getStudentRequestsWithFilters(int $studentId, array $filters, int $perPage = 15)
    {
        $query = $this->model->newQuery()
            ->where('student_id', $studentId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['name'])) {
            $query->whereHas('requestType', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }

        $query->orderBy('submission_date', 'desc');

        return $query->paginate($perPage);
    }

    public function getStaffRequestsWithFilters(int $userId, array $filters, int $perPage = 15)
    {
        $user = User::with('roles')->find($userId);
        $spatieRoles = $user->roles->pluck('name')->toArray();
        if (empty($spatieRoles)) {
            return $this->model->newQuery()->paginate(0);
        }
        $requestRoles = $this->mapSpatieRolesToRequestRoles($spatieRoles);
        $waitingStatuses = $this->getWaitingStatusesFromSpatieRoles($spatieRoles);

        if (empty($requestRoles) || empty($waitingStatuses)) {
            return $this->model->newQuery()->paginate(0);
        }
        $policy = new RequestPolicy();
        $authorized = $policy->viewCollegeRequests($user, $filters['college_id'], $requestRoles[0]);
        if (!$authorized) {
            abort(403, 'ليس لديك صلاحية للوصول إلى طلبات هذه الكلية.');
        }
        $query = $this->model->with([
            'course.universalCourse',
            'processedBy.person',
            'requestType',
            'student.person',
        ])->newQuery()
            ->whereIn('requests.status', $waitingStatuses)
            ->where(function ($query) use ($userId, $requestRoles) {
                $query->orWhereDoesntHave('assignedUsers', function ($q) use ($requestRoles) {
                    $q->whereIn('request_user.role', $requestRoles);
                })
                    ->orWhereHas('assignedUsers', function ($q) use ($userId, $requestRoles) {
                        $q->where('request_user.user_id', $userId)
                            ->where('request_user.status', 'pending')
                            ->whereIn('request_user.role', $requestRoles);
                    });
            })
            ->whereHas('student', function ($q) use ($filters) {
                $q->where('college_id', $filters['college_id']);
            })
            ->orderBy('submission_date', 'desc');
        if (!empty($filters['name'])) {
            $query->whereHas('requestType', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['name'] . '%');
            });
        }

        $query->orderBy('submission_date', 'desc');
        return $query->paginate($perPage);
    }

    public function findWithDetailsAndMedia(int $requestId, int $studentId): ?Request
    {
        $request = $this->model->newQuery()
            ->with(['requestType:id,name,description', 'media'])
            ->where('id', $requestId)
            ->where('student_id', $studentId)
            ->first();

        if ($request && $request->status !== 'completed') {
            $request->pdf_path = null;
        }

        return $request;
    }

    public function staffFindWithDetailsAndMedia(int $requestId): ?Request
    {
        return $this->model->newQuery()
            ->with([
                'course.universalCourse',
                'processedBy.person',
                'media',
                'requestType.requestTypeMedia',
                'student.person'
            ])
            ->where('id', $requestId)
            ->first();
    }
    public function getRequestUser(int $userId, int $requestId): ?RequestUser
    {
        return RequestUser::where('user_id', $userId)
            ->where('request_id', $requestId)
            ->first();
    }
    public function updateStatus(int $requestId, string $status): ?Request
    {
        $r = $this->model->find($requestId);
        if (!$r) {
            return null;
        }
        $r->status = $status;
        $r->save();
        return $r;
    }

    public function getStudentRequests(int $studentId, int $perPage = 15)
    {
        return $this->model
            ->with([
                'course.universalCourse',
                'processedBy.person'
            ])
            ->where('student_id', $studentId)
            ->orderByDesc('submission_date')
            ->paginate($perPage);
    }

    public function findRequestDetails(int $requestId, int $studentId): ?Request
    {
        return $this->model
            ->with([
                'course.universalCourse',
                'processedBy.person',
                'media',
                'requestType.requestTypeMedia'
            ])
            ->where('id', $requestId)
            ->where('student_id', $studentId)
            ->first();
    }

    public function assignRequestToUser(int $requestId, int $userId, int $collegeId): array
    {
        $request = $this->model->with(['student'])->find($requestId);
        if (!$request) {
            throw new \Exception('الطلب غير موجود.');
        }
        if (!str_starts_with($request->status, 'waiting_')) {
            throw new \Exception('الطلب ليس في حالة انتظار التوقيع.');
        }
        $roleFromStatus = substr($request->status, 8);
        $user = User::with('roles')->find($userId);
        if (!$user) {
            throw new \Exception('المستخدم غير موجود.');
        }
        $spatieRoles = $user->roles->pluck('name')->toArray();
        $requestRoles = $this->mapSpatieRolesToRequestRoles($spatieRoles);
        if (!in_array($roleFromStatus, $requestRoles)) {
            throw new \Exception('ليس لديك الصلاحية لاستلام هذا الطلب.');
        }
        $policy = new RequestPolicy();
        if (!$policy->viewCollegeRequests($user, $collegeId, $roleFromStatus)) {
            throw new \Exception('أنت لا تنتمي لهذه الكلية.');
        }
        if ($request->student->college_id != $collegeId) {
            throw new \Exception('الطلب لا يتبع الكلية المحددة.');
        }
        $existingRecord = RequestUser::where('request_id', $requestId)
            ->where('role', $roleFromStatus)
            ->exists();
        if ($existingRecord) {
            throw new \Exception('هذا الطلب مكلف بالفعل لموظف من نفس الدور.');
        }
        $selfAssigned = RequestUser::where('request_id', $requestId)
            ->where('user_id', $userId)
            ->where('role', $roleFromStatus)
            ->where('status', 'pending')
            ->exists();
        if ($selfAssigned) {
            throw new \Exception('أنت مكلف بهذا الطلب بالفعل.');
        }
        RequestUser::create([
            'user_id' => $userId,
            'role' => $roleFromStatus,
            'request_id' => $requestId,
            'status' => 'pending',
            'signed_at' => null,
        ]);
        return [
            'request_id' => $requestId,
            'status' => $request->status,
        ];
    }

    public function approveRequest(int $requestId, int $userId, string $decision, string $decision_reason): array
    {
        $requestUser = RequestUser::with(['request.student', 'request.requestType'])
            ->where('request_id', $requestId)
            ->where('user_id', $userId)
            ->first();

        if (!$requestUser) {
            throw new \Exception('سجل التعيين غير موجود أو لا يخصك.');
        }

        if ($requestUser->status !== 'pending') {
            throw new \Exception('هذا الطلب تم معالجته بالفعل.');
        }

        $request = $requestUser->request;

        if (!str_starts_with($request->status, 'waiting_')) {
            throw new \Exception('الطلب ليس في حالة انتظار التوقيع.');
        }

        $roleFromStatus = substr($request->status, 8);
        if ($requestUser->role !== $roleFromStatus) {
            throw new \Exception('دور المستخدم لا يطابق حالة الطلب.');
        }

        // ===== حالة الرفض =====
        if ($decision === 'rejected') {
            $requestUser->update([
                'status' => 'rejected',
                'signed_at' => now(),
            ]);

            $request->update([
                'status' => 'rejected',
                'decision_date' => now(),
                'processed_by_staff_id' => $userId,
                'decision_reason' => $decision_reason,
            ]);

            return [
                'request_id' => $request->id,
                'status' => 'rejected',
                'decision' => 'rejected',
                'decision_reason' => $decision_reason,
            ];
        }

        // ===== حالة الموافقة =====
        $latestSignature = UserSignature::getLatestForUser($userId);
        $requestUser->update([
            'status' => 'approved',
            'signed_at' => now(),
            'user_signature_id' => $latestSignature?->id,
        ]);

        // تحديد الدور التالي
        $requiredRoles = $request->requestType->getRequiredRoles();
        $currentIndex = array_search($roleFromStatus, $requiredRoles);

        if ($currentIndex === false || $currentIndex === count($requiredRoles) - 1) {
            // آخر دور
            $newStatus = 'completed';
            $request->update([
                'status' => $newStatus,
                'decision_date' => now(),
                'decision_reason' => $decision_reason,
                'processed_by_staff_id' => $userId,
            ]);
        } else {
            $nextRole = $requiredRoles[$currentIndex + 1];
            $newStatus = 'waiting_' . $nextRole;
            $request->update([
                'status' => $newStatus,
            ]);
        }

        // 🔥 التحقق: إذا كان الطلب يحتاج PDF، نطلق Job لتوليد PDF مع التوقيعات
        if ($this->shouldGeneratePdf($request)) {
            // نغير الحالة إلى generating_... ثم نطلق الـ Job
            // لأن الـ Job هو المسؤول عن توليد PDF وتغيير الحالة إلى waiting_... أو completed
            if ($currentIndex === false || $currentIndex === count($requiredRoles) - 1) {
                $generatingStatus = 'generating_final_pdf';
            } else {
                $nextRole = $requiredRoles[$currentIndex + 1];
                $generatingStatus = 'generating_' . $nextRole . '_pdf';
            }
            $request->update(['status' => $generatingStatus]);
            GenerateRequestPdfJob::dispatch($request);
        } else {
            // لا يحتاج PDF: الحالة موجودة بالفعل (waiting_... أو completed)
            // لا حاجة لفعل شيء إضافي
        }

        return [
            'request_id' => $request->id,
            'status' => $newStatus,
            'decision' => 'approved',
            'decision_reason' => $decision_reason,
        ];
    }
    public function canUpdateGrade(User $user, StudentCoursePart $studentCoursePart): array
    {
        if ($user->hasRole('Examination')) {
            return [
                'status'  => true,
                'message' => 'Allowed by Examination department.',
                'code'    => 200,
            ];
        }
        $requestTypeName = null;

        if ($user->hasRole('Instructor')) {
            $requestTypeName = 'طلب إعادة تصحيح';
        } elseif ($user->hasRole('TeachingAssistant')) {
            $requestTypeName = 'اعتراض على علامة';
        } else {
            return [
                'status' => false,
                'message' => 'Unauthorized.',
                'code' => 403,
            ];
        }

        $requestType = RequestType::where('name', $requestTypeName)->first();

        if (!$requestType) {
            return [
                'status' => false,
                'message' => 'Request type not found.',
                'code' => 404,
            ];
        }

        $exists = $this->model
            ->where('request_type_id', $requestType->id)
            ->where('student_id', $studentCoursePart->studentCourse->student_id)
            ->where('course_id', $studentCoursePart->studentCourse->course_id)
            ->where('status', 'pending')
            ->exists();

        if (!$exists) {
            return [
                'status' => false,
                'message' => 'No valid objection request found.',
                'code' => 403,
            ];
        }

        return [
            'status' => true,
            'message' => '',
            'code' => 200,
        ];
    }
}
