<?php

namespace App\DTOs;

use App\Models\Request;

class RequestDetailsDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $student_id,
        public readonly string $request_type_id,
        public readonly ?string $reason,
        public readonly ?string $submission_date,
        public readonly string $status,
        public readonly ?string $decision_date,
        public readonly ?string $decision_reason,
        public readonly ?string $processed_by_staff_id,
        public readonly ?string $course_id,
        public readonly ?string $request_type_name,
        public readonly ?string $request_type_description,
        public readonly array $media
    ) {}

    public static function fromModel(Request $request): self
    {
        $request->loadMissing(['requestType', 'media']);
        $submission = null;
        $decision = null;
        if ($request->submission_date instanceof \DateTime) {
            $submission = $request->submission_date->format('Y-m-d H:i:s');
        } elseif (!empty($request->submission_date)) {
            $submission = (string) $request->submission_date;
        }

        if ($request->decision_date instanceof \DateTime) {
            $decision = $request->decision_date->format('Y-m-d H:i:s');
        } elseif (!empty($request->decision_date)) {
            $decision = (string) $request->decision_date;
        }

        $media = collect($request->media)->map(fn($item) => RequestMediaDTO::fromModel($item)->toArray())->toArray();

        return new self(
            (string) $request->id,
            (string) $request->student_id,
            (string) $request->request_type_id,
            $request->reason ? (string) $request->reason : null,
            $submission,
            (string) $request->status,
            $decision,
            $request->decision_reason ? (string) $request->decision_reason : null,
            $request->processed_by_staff_id ? (string) $request->processed_by_staff_id : null,
            $request->course_id ? (string) $request->course_id : null,
            $request->requestType?->name ? (string) $request->requestType->name : null,
            $request->requestType?->description ? (string) $request->requestType->description : null,
            $media
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'request_type_id' => $this->request_type_id,
            'reason' => $this->reason,
            'submission_date' => $this->submission_date,
            'status' => $this->status,
            'decision_date' => $this->decision_date,
            'decision_reason' => $this->decision_reason,
            'processed_by_staff_id' => $this->processed_by_staff_id,
            'course_id' => $this->course_id,
            'request_type_name' => $this->request_type_name,
            'request_type_description' => $this->request_type_description,
            'media' => $this->media,
        ];
    }
}
