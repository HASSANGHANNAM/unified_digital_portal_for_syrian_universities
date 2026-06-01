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
}
