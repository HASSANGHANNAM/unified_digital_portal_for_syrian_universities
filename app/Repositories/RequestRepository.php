<?php

namespace App\Repositories;

use App\Models\Request;

class RequestRepository
{
    public function __construct(private Request $model) {}

    public function create(array $data): Request
    {
        $data['status'] = $data['status'] ?? Request::STATUS_PENDING;
        $data['submission_date'] = $data['submission_date'] ?? now();
        $data['decision_date'] = $data['decision_date'] ?? null;
        $data['decision_reason'] = $data['decision_reason'] ?? null;
        $data['processed_by_staff_id'] = $data['processed_by_staff_id'] ?? null;
        $data['course_id'] = $data['course_id'] ?? null;
        return $this->model->create($data);
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

    public function findWithDetailsAndMedia(int $requestId, int $studentId): ?Request
    {
        return $this->model->newQuery()
            ->with(['requestType:id,name,description', 'media'])
            ->where('id', $requestId)
            ->where('student_id', $studentId)
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
}
